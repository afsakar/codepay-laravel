<?php

namespace App\Http\Livewire\Currencies;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Currency;
use Livewire\Component;

class CurrencyList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public Currency $editing;
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
            'editing.name' => 'required|min:3|unique:currencies,name,'.$this->editing->id,
            'editing.code' => 'required|size:3|unique:currencies,code,'.$this->editing->id,
            'editing.symbol' => 'required|unique:currencies,symbol,'.$this->editing->id,
            'editing.status' => 'required',
            'editing.position' => 'required|in:after,before',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.code' => __('Code'),
            'editing.symbol' => __('Symbol'),
            'editing.status' => __('Status'),
            'editing.position' => __('Position'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankCurrency();
    }

    public function makeBlankCurrency()
    {
        return Currency::make(['status' => 'active', 'position' => 'after']);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Currency $type)
    {
        $this->useCachedRows();
        if($this->editing->isNot($type)) $this->editing = $type;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankCurrency();
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
        $this->mount();
        $this->render();
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankCurrency();
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
        $query = Currency::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('symbol', 'like', '%'.$search.'%')
                ->orWhere('code', 'like', '%'.$search.'%'));
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
        return view('livewire.currencies.currency-list', [
            'currencies' => $this->rows
        ]);
    }
}
