<?php

namespace App\Http\Livewire\Accounts;

use App\Exports\AccountsExport;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Currency;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Maatwebsite\Excel\Facades\Excel;

class AccountList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, LivewireAlert, WithCachedRows, WithToastNotification;

    public Account $editing;
    public $accountTypes;
    public $currencies;
    public $createMode = false;
    public $deleteModal = false;
    public $singleDelete = false;
    public $editingModal = false;
    public $currencyPosition = "after";

    protected $listeners = [
        'save',
    ];

    protected $queryString = [];

    public $filters = [
        'search' => "",
        'status' => "",
        'balance-min' => null,
        'balance-max' => null,
        'date-min' => null,
        'date-max' => null,
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:accounts,name,'.$this->editing->id,
            'editing.account_type_id' => 'required',
            'editing.owner' => 'nullable',
            'editing.description' => 'required|min:3',
            'editing.balance' => 'required|numeric',
            'editing.status' => 'required',
            'editing.currency_id' => 'required',
            'editing.currency_status' => 'required',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Account Name'),
            'editing.account_type_id' => __('Account Type'),
            'editing.owner' => __('Account Owner'),
            'editing.description' => __('Account Description'),
            'editing.balance' => __('Account Balance'),
            'editing.status' => __('Status'),
            'editing.currency_id' => __('Account Currency'),
            'editing.currency_status' => __('Currency Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankAccount();
        $this->accountTypes = AccountType::where('status', 'active')->get();
        $this->currencies = Currency::where('status', 'active')->get();
    }

    public function makeBlankAccount()
    {
        return Account::make(['status' => 'active', 'currency_id' => 1, 'currency_status' => $this->currencyPosition]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Account $account)
    {
        $this->useCachedRows();
        if($this->editing->isNot($account)) $this->editing = $account;
        $this->editingModal = true;
        $this->currencyPosition = $this->editing['currency_status'];
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankAccount();
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
        $this->makeBlankAccount();
        $this->resetValidation();
    }

    public function exportPdf()
    {
        return (new AccountsExport($this->selected))->download('accounts-list.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportExcel()
    {
        return Excel::download(new AccountsExport($this->selected), 'accounts-list.xlsx');
    }

    public function deleteSelected()
    {
        try {
            $this->selectedRowsQuery->delete();
            $this->notify('Selected records have been deleted successfully!');
        }catch (\Exception $e) {
            $this->notify('You can\'t delete '. $this->editing->name .', because it is being used by other items!', 'error');
        }
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function changeCurrencyPosition($currency)
    {
        $this->currencyPosition = $currency;
    }
    /* Editing / Creating / Deleting / Exporting */

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
        $query = Account::query()->withSum('revenue', 'amount')
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['balance-min'], fn($query, $balance) => $query
                ->having(DB::raw('IF(count(revenue_sum_amount) > 0, SUM(revenue_sum_amount + balance), balance)'), '>=', $balance)->groupBy('id'))
            ->when($this->filters['balance-max'], fn($query, $balance) => $query
                ->having(DB::raw('IF(count(revenue_sum_amount) > 0, SUM(revenue_sum_amount + balance), balance)'), '<=', $balance)->groupBy('id'))
            ->when($this->filters['date-min'], fn($query, $created_at) => $query->where('created_at', '>=', Carbon::parse($created_at)))
            ->when($this->filters['date-max'], fn($query, $created_at) => $query->where('created_at', '<=', Carbon::parse($created_at)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%')->orWhere('owner', 'like', '%'.$search.'%'));
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
        return view('livewire.accounts.account-list', [
            'accounts' => $this->rows
        ]);
    }
}
