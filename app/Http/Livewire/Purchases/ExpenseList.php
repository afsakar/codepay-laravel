<?php

namespace App\Http\Livewire\Purchases;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Account;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use App\Models\Corporation;
use App\Models\Currency;
use App\Models\Category;
use App\Exports\AccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Http\Livewire\DataTable\WithPerPagePagination;

class ExpenseList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public Expense $editing;
    public Expense $detail;

    public $acc;
    public $amount;
    public $symbol;
    public $account;
    public $accounts;
    public $category;
    public $corporation;
    public $corporations;
    public $categories;
    public $currencies;
    public $currency_status;

    public $createMode = false;
    public $deleteModal = false;
    public $detailModal = false;
    public $singleDelete = false;
    public $editingModal = false;

    protected $listeners = [
        'save',
    ];

    public $filters = [
        'search' => "",
        'type' => "",
        'category_id' => "",
        'corporation_id' => "",
        'amount-min' => null,
        'amount-max' => null,
        'date-min' => null,
        'date-max' => null,
    ];

    public function rules()
    {
        return [
            'editing.account_id' => 'required',
            'editing.corporation_id' => 'required',
            'editing.category_id' => 'required',
            'editing.company_id' => 'required',
            'editing.description' => 'nullable',
            'editing.amount' => 'required|numeric',
            'editing.exchange_rate' => 'required|numeric',
            'editing.type' => 'required|in:formal,informal',
            'editing.due_at' => 'required',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.account_id' => __('Account'),
            'editing.corporation_id' => __('Corporation'),
            'editing.category_id' => __('Category'),
            'editing.description' => __('Description'),
            'editing.amount' => __('Amount'),
            'editing.exchange_rate' => __('Exchange Rate'),
            'editing.type' => __('Type'),
            'editing.due_at' => __('Due At'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankExpense();
        $this->accounts = Account::where('status', 'active')->get();
        $this->corporations = Corporation::where(['status' => 'active'])->get();
        $this->categories = Category::where('type', 'expense')->get();
        $this->detail = $this->makeBlankExpense();
    }

    public function makeBlankExpense()
    {
        return Expense::make([
            'type' => '',
            'account_id' => '',
            'corporation_id' => '',
            'company_id' => get_company_info()->id,
            'category_id' => '',
            'description' => '',
            'amount' => '',
            'exchange_rate' => '',
            'due_at' => null,
        ]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Expense $expense)
    {
        $this->useCachedRows();
        if($this->editing->isNot($expense)) $this->editing = $expense;
        $this->editingModal = true;
        $this->symbol = Currency::where('id', $this->editing->account->currency_id)->first()->symbol;
        $this->currency_status = Account::where('id', $this->editing->account_id)->first()->currency_status;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankExpense();
        $this->editingModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        if($this->createMode) {
            $this->createMode = false;
            $this->notify('Record has been created successfully!');
        }else{
            $this->notify('Record has been updated successfully!');
        }
        $this->editingModal = false;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankExpense();
        $this->resetValidation();
        $this->acc = null;
        $this->symbol = null;
    }

    public function deleteSelected()
    {
        try {
            $this->selectedRowsQuery->delete();
            $this->notify('Selected records have been deleted successfully!');
        }catch (\Exception $e) {
            $this->notify('You can\'t delete selected record(s), because it is being used by other items!', 'error');
        }
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function toggleDetailModal(Expense $expense)
    {
        $this->detail = $expense;
        $this->detailModal = true;
        $this->corporation = $expense->corporation()->first();
        $this->account = $expense->account()->first();
        $this->category = $expense->category()->first();
        $this->amount = $expense->getAmountWithCurrencyAttribute();
    }

    public function closeDetailModal()
    {
        $this->detailModal = false;
        $this->detail = $this->makeBlankExpense();
        $this->corporation = "";
        $this->account = "";
        $this->category = "";
        $this->amount = "";
    }
    /* Editing / Creating / Deleting / Exporting */

    public function changeAccount(Account $acc)
    {
        $this->acc = $acc;
        $this->symbol = $acc->currency()->first()->symbol;
        $this->currency_status = $acc->currency_status;
        $this->editing->exchange_rate = in_array($this->acc->currency()->first()->code, ['USD', 'EUR', 'GBP']) ? ($this->acc->currency_id != 1 ? currency_rates($this->acc->currency()->first()->code)['selling'] : 1) : 1;

    }

    public function toggleFilters()
    {
        $this->useCachedRows();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Expense::query()->where('company_id', get_company_info()->id)
            ->when($this->filters['type'], fn($query, $type) => $query->where('type', $type))
            ->when($this->filters['amount-min'], fn($query, $amount) => $query->where('amount', '>=', $amount))
            ->when($this->filters['amount-max'], fn($query, $amount) => $query->where('amount', '<=', $amount))
            ->when($this->filters['date-min'], fn($query, $due_at) => $query->where('due_at', '>=', Carbon::parse($due_at)))
            ->when($this->filters['date-max'], fn($query, $due_at) => $query->where('due_at', '<=', Carbon::parse($due_at)))
            ->when($this->filters['category_id'], fn($query, $category_id) => $query->where('category_id', $category_id))
            ->when($this->filters['corporation_id'], fn($query, $corporation_id) => $query->where('corporation_id', $corporation_id))
            ->when($this->filters['search'], fn($query, $search) => $query->where('description', 'like', '%'.$search.'%'))->orderBy('due_at', 'desc');
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function() {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function render()
    {
        return view('livewire.purchases.expense-list', [
            'expenses' => $this->rows
        ]);
    }
}
