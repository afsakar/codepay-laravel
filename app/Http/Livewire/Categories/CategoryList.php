<?php

namespace App\Http\Livewire\Categories;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public Category $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $editingModal = false;

    protected $queryString = [];

    public $filters = [
        'search' => "",
        'status' => "",
        'type' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:categories,name,'.$this->editing->id,
            'editing.type' => 'required|in:income,expense',
            'editing.status' => 'required|in:active,inactive',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.type' => __('Type'),
            'editing.status' => __('Status'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankCategory();
    }

    public function makeBlankCategory()
    {
        return Category::make(['status' => 'active', 'type' => '']);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Category $category)
    {
        $this->useCachedRows();
        if($this->editing->isNot($category)) $this->editing = $category;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankCategory();
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
        $this->makeBlankCategory();
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
        $query = Category::query()
            ->when($this->filters['type'], fn($query, $type) => $query->where('type', $type))
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
        return view('livewire.categories.category-list', [
            'categories' => $this->rows
        ]);
    }
}
