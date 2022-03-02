<?php

namespace App\Http\Livewire\Materials;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\MaterialCategory;
use Livewire\Component;

class MaterialCategoryList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public MaterialCategory $editing;
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
            'editing.name' => 'required|min:3|unique:units,name,'.$this->editing->id,
            'editing.created_by' => 'required|exists:users,id',
            'editing.status' => 'required|in:active,inactive',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.status' => __('Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankMaterialCategory();
    }

    public function makeBlankMaterialCategory()
    {
        return MaterialCategory::make(['status' => 'active', 'created_by' => auth()->user()->id]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(MaterialCategory $material_category)
    {
        $this->useCachedRows();
        if($this->editing->isNot($material_category)) $this->editing = $material_category;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankMaterialCategory();
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
        $this->makeBlankMaterialCategory();
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
        $material_category = MaterialCategory::find($id);
        $material_category->status == "active" ? $material_category->status = "inactive" : $material_category->status = "active";
        $material_category->save();
        $this->notify('Material Category status updated successfully.');
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
        $query = MaterialCategory::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))->orderBy('name');
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
        return view('livewire.materials.material-category-list', [
            'material_categories' => $this->rows
        ]);
    }
}
