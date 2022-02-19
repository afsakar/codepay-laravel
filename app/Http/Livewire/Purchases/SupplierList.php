<?php

namespace App\Http\Livewire\Purchases;

use App\Models\Supplier;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithToastNotification;

class SupplierList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public Supplier $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $singleDelete = false;
    public $editingModal = false;

    protected $listeners = [
        'save',
    ];

    public $filters = [
        'search' => "",
        'status' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:suppliers,name,'.$this->editing->id,
            'editing.owner' => 'nullable',
            'editing.tel_number' => 'nullable|numeric|digits:10',
            'editing.gsm_number' => 'nullable|numeric|digits:10',
            'editing.fax_number' => 'nullable|numeric|digits:10',
            'editing.email' => 'nullable|email',
            'editing.address' => 'nullable|min:3',
            'editing.tax_office' => 'nullable|min:3',
            'editing.tax_number' => 'nullable|numeric|unique:suppliers,tax_number,'.$this->editing->id,
            'editing.status' => 'required',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.owner' => __('Owner'),
            'editing.status' => __('Status'),
            'editing.tel_number' => __('Phone Number'),
            'editing.gsm_number' => __('GSM Number'),
            'editing.fax_number' => __('Fax Number'),
            'editing.email' => __('Email Address'),
            'editing.address' => __('Address'),
            'editing.tax_office' => __('Tax Office'),
            'editing.tax_number' => __('Tax Number'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankSupplier();
    }

    public function makeBlankSupplier()
    {
        return Supplier::make(['status' => 'active']);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Supplier $supplier)
    {
        $this->useCachedRows();
        if($this->editing->isNot($supplier)) $this->editing = $supplier;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankSupplier();
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
        $this->makeBlankSupplier();
        $this->resetValidation();
    }

    public function deleteSelected()
    {
        try {
            $this->selectedRowsQuery->delete();
            $this->notify('Selected records have been deleted successfully!');
        }catch (\Exception $e) {
            $this->notify('An error occurred while deleting selected records!', 'error');
        }
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
        $query = Supplier::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['search'], fn($query, $search) => $query
                ->where('name', 'like', '%'.$search.'%'));
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
        return view('livewire.purchases.supplier-list', [
            'suppliers' => $this->rows
        ]);
    }
}
