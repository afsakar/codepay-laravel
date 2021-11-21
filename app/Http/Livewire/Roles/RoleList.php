<?php

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Role;
use Livewire\Component;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;

class RoleList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public Role $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $editingModal = false;
    public $permissions = [];

    protected $queryString = [];

    public $filters = [
        'search' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:roles,name,'.$this->editing->id,
            'editing.description' => 'nullable',
            'permissions.*' => 'nullable',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Role Name'),
            'editing.description' => __('Description'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankRole();
    }

    public function makeBlankRole()
    {
        return Role::make();
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Role $role)
    {
        $this->useCachedRows();
        if($this->editing->isNot($role)) $this->editing = $role;
        $this->permissions = $role->perms;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankRole();
        $this->permissions = [];
        $this->editingModal = true;
    }

    public function save()
    {
        $data = [
            'name' => $this->editing->name,
            'description' => $this->editing->description,
            'permissions' => json_encode($this->permissions),
        ];

        if($this->createMode) {
            $this->createMode = false;
            Role::create($data);
            $this->notify('Record has been created successfully!');
        }else{
            Role::where('id', $this->editing->id)->update($data);
            $this->notify('Record has been updated successfully!');
        }
        $this->editingModal = false;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankRole();
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
        $query = Role::query()
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
        return view('livewire.roles.role-list', [
            'roles' => $this->rows
        ]);
    }
}
