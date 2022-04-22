<?php

namespace App\Http\Livewire\Materials;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Currency;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Tax;
use App\Models\Unit;
use Livewire\Component;

class MaterialList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public Material $editing;
    public $taxes;
    public $units;
    public $symbol;
    public $position;
    public $material_categories;
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
        'price' => null,
        'material_category_id' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:materials,name,'.$this->editing->id,
            'editing.sku' => 'required|unique:materials,name,'.$this->editing->id,
            'editing.price' => 'required|numeric',
            'editing.quantity' => 'required|numeric',
            'editing.tax_id' => 'required',
            'editing.material_category_id' => 'required',
            'editing.unit_id' => 'required',
            'editing.currency_id' => 'required',
            'editing.type' => 'nullable',
            'editing.description' => 'nullable',
            'editing.status' => 'required',
            'editing.created_by' => 'required|exists:users,id',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Material Name'),
            'editing.sku' => __('SKU Code'),
            'editing.price' => __('Sale Price'),
            'editing.quantity' => __('Quantity'),
            'editing.tax_id' => __('Tax'),
            'editing.material_category_id' => __('Material Category'),
            'editing.unit_id' => __('Unit'),
            'editing.currency_id' => __('Currency'),
            'editing.type' => __('Type'),
            'editing.description' => __('Description'),
            'editing.status' => __('Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankMaterial();
        $this->taxes = Tax::where('status', 'active')->orderBy('name')->get();
        $this->material_categories = MaterialCategory::where('status', 'active')->orderBy('name')->get();
        $this->units = Unit::where('status', 'active')->orderBy('name')->get();
        $this->currencies = Currency::where('status', 'active')->orderBy('name')->get();
    }

    public function makeBlankMaterial()
    {
        return Material::make([
            'status' => 'active',
            "tax_id" => "",
            'material_category_id' => "",
            'quantity' => 1,
            "unit_id" => "",
            "currency_id" => 1,
            "type" => "",
            "description" => "",
            "created_by" => auth()->user()->id
        ]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Material $material)
    {
        $this->useCachedRows();
        if($this->editing->isNot($material)) $this->editing = $material;
        $this->symbol = Currency::where('id', $this->editing->currency_id)->first()->symbol;
        $this->position = Currency::where('id', $this->editing->currency_id)->first()->position;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankMaterial();
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
        $this->makeBlankMaterial();
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

    public function toggleSwitch(Material $material)
    {
        $material->status == "active" ? $material->status = "inactive" : $material->status = "active";
        $material->save();
        $this->notify('Material status updated successfully.');
        $this->mount();
        $this->render();
    }
    /* Editing / Creating / Deleting / Exporting */

    public function changeCurrency(Currency $currency)
    {
        $this->symbol = $currency->symbol;
        $this->position = $currency->position;
        $this->render();
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
        $query = Material::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['price'], fn($query, $price) => $query->where('price', '>=', $price))
            ->when($this->filters['material_category_id'], fn($query, $material_category_id) => $query->where('material_category_id', '>=', $material_category_id))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('sku', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%'));
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
        return view('livewire.materials.material-list', [
            'materials' => $this->rows
        ]);
    }
}
