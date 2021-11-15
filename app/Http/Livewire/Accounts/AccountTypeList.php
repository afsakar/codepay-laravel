<?php

namespace App\Http\Livewire\Accounts;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\AccountType;
use Livewire\Component;

class AccountTypeList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, LivewireAlert, WithCachedRows;

    public $search = "";
    public AccountType $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $editingModal = false;

    protected $queryString = [];

    public $filters = [
        'search' => "",
        'status' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:account_types,name,'.$this->editing->id,
            'editing.status' => 'required',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Account Type Name'),
            'editing.status' => __('Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankAccountType();
    }

    public function makeBlankAccountType()
    {
        return AccountType::make(['status' => 'active']);
    }



    /* Editing / Creating / Deleting / Exporting */
    public function edit(AccountType $type)
    {
        $this->useCachedRows();
        if($this->editing->isNot($type)) $this->editing = $type;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankAccountType();
        $this->editingModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        if($this->createMode) {
            $this->createMode = false;
            $this->dispatchBrowserEvent('notify', 'Record has been created successfully!');
        }else{
            $this->dispatchBrowserEvent('notify', 'Record has been updated successfully!');
        }
        $this->editingModal = false;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankAccountType();
        $this->resetValidation();
    }

    public function deleteSelected()
    {
        $this->selectedRowsQuery->delete();
        $this->dispatchBrowserEvent('notify', 'Selected items deleted successfully!');
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
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
        $query = AccountType::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
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
        return view('livewire.accounts.account-type-list', [
            'types' => $this->rows
        ]);
    }
}
