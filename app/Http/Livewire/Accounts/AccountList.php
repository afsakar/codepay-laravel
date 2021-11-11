<?php

namespace App\Http\Livewire\Accounts;

use App\Exports\AccountsExport;
use Carbon\Carbon;
use App\Models\Account;
use Livewire\Component;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Maatwebsite\Excel\Facades\Excel;

class AccountList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, LivewireAlert, WithCachedRows;

    public $search = "";
    public $editingModal = false;
    public $createMode = false;
    public $deleteModal = false;
    public $singleDelete = false;
    public Account $editing;

    protected $listeners = [
        'save',
        'deleteAlert',
        'deleteSingle',
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

    protected $rules = [
        'editing.name' => 'required|min:3',
        'editing.description' => 'required|min:3',
        'editing.balance' => 'required|numeric',
        'editing.status' => 'required',
        'editing.currency' => 'required',
        'editing.currency_status' => 'required',
    ];

    public function mount()
    {
        $this->editing = $this->makeBlankAccount();
    }

    public function makeBlankAccount()
    {
        return Account::make(['status' => 'active', 'currency' => 'TL', 'currency_status' => 'after']);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Account $account)
    {
        $this->useCachedRows();
        if($this->editing->isNot($account)) $this->editing = $account;
        $this->editingModal = true;
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
            $this->dispatchBrowserEvent('notify', 'Record has been created successfuly!');
        }else{
            $this->dispatchBrowserEvent('notify', 'Record has been updated successfuly!');
        }
        $this->editingModal = false;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->resetValidation();
    }

    public function deleteSelected()
    {
        $this->selectedRowsQuery->delete();
        $this->dispatchBrowserEvent('notify', 'Selected Accounts deleted successfuly!');
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function deleteSingle(Account $account)
    {
        $this->editing = $account;
        $this->editing->delete();
        $this->dispatchBrowserEvent('notify', 'Account deleted successfuly!');
        $this->singleDelete = false;
    }

    public function exportExcel()
    {
        return Excel::download(new AccountsExport($this->selected), 'accounts-list.xlsx');
    }

    public function exportPdf()
    {
        return (new AccountsExport($this->selected))->download('accounts-list.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
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
        $query = Account::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['balance-min'], fn($query, $balance) => $query->where('balance', '>=', $balance))
            ->when($this->filters['balance-max'], fn($query, $balance) => $query->where('balance', '<=', $balance))
            ->when($this->filters['date-min'], fn($query, $created_at) => $query->where('created_at', '>=', Carbon::parse($created_at)))
            ->when($this->filters['date-max'], fn($query, $created_at) => $query->where('created_at', '<=', Carbon::parse($created_at)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'));


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
