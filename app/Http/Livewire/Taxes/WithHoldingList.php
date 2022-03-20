<?php

namespace App\Http\Livewire\Taxes;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\WithHolding;
use Livewire\Component;

class WithHoldingList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public WithHolding $editing;
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
            'editing.name' => 'required|min:3|unique:with_holdings,name,'.$this->editing->id,
            'editing.rate' => 'required|numeric',
            'editing.status' => 'required|in:active,inactive',
            'editing.created_by' => 'required|exists:users,id',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.rate' => __('Rate'),
            'editing.status' => __('Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankWithHolding();
    }

    public function makeBlankWithHolding()
    {
        return WithHolding::make(['status' => 'active', 'created_by' => auth()->user()->id]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(WithHolding $with_holding)
    {
        $this->useCachedRows();
        if($this->editing->isNot($with_holding)) $this->editing = $with_holding;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankWithHolding();
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
        $this->makeBlankWithHolding();
        $this->resetValidation();
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
        $this->mount();
        $this->render();
    }

    public function toggleSwitch($id)
    {
        $tax = WithHolding::find($id);
        $tax->status == "active" ? $tax->status = "inactive" : $tax->status = "active";
        $tax->save();
        $this->notify('Withholding Tax status updated successfully.');
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
        $query = WithHolding::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%')->orWhere('rate', 'like', '%'.$search.'%'));
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
        return view('livewire.taxes.with-holding-list', [
            'with_holdings' => $this->rows
        ]);
    }
}
