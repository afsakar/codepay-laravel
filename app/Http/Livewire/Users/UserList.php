<?php

namespace App\Http\Livewire\Users;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithToastNotification;


class UserList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public User $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $singleDelete = false;
    public $editingModal = false;

    protected $listeners = [
        'save',
    ];

    protected $queryString = [];

    public $filters = [
        'search' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3',
            'editing.email' => 'required|email|unique:users,email,' . $this->editing->id,
            'editing.king' => [
                $this->createMode ? 'required' : "nullable",
                'confirmed',
                'min:8',
            ],
            'editing.king_confirmation' => 'nullable',
            'editing.role_id' => 'nullable|exists:roles,id',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Full Name'),
            'editing.email' => __('Email Address'),
            'editing.king' => __('Password'),
            'editing.king_confirmation' => __('Password Confirmation'),
            'editing.role_id' => __('User Role'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankUser();
    }

    public function makeBlankUser()
    {
        return User::make(['role_id' => 3]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(User $user)
    {
        $this->useCachedRows();
        if ($this->editing->isNot($user)) $this->editing = $user;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if ($this->editing->getKey()) $this->editing = $this->makeBlankUser();
        $this->editingModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->createMode) {
            $data = [
                'name' => $this->editing->name,
                'email' => $this->editing->email,
                'password' => Hash::make($this->editing->king),
                'role_id' => $this->editing->role_id,
            ];

            User::create($data);
            $this->createMode = false;
            $this->notify('Record has been created successfully!');
        } else {
            $password = $this->editing->king != "" ? Hash::make($this->editing->king) : User::where('id', $this->editing->id)->first()->password;

            $data = [
                'name' => $this->editing->name,
                'email' => $this->editing->email,
                'password' => $password,
                'role_id' => $this->editing->role_id,
            ];

            User::where('id', $this->editing->id)->update($data);
            $this->notify('Record has been updated successfully!');
            $this->editing->king = "";
            $this->editing->king_confirmation = "";
        }
        $this->editingModal = false;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankUser();
        $this->resetValidation();
    }

    public function deleteSelected()
    {
        try {
            $this->selectedRowsQuery->delete();
            $this->notify('Selected records have been deleted successfully!');
        } catch (\Exception $e) {
            $this->notify('Error occurred while deleting selected records!', 'error');
        }
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->editingModal = false;
        $this->createMode = false;
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
        $query = User::query()
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%'));
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function render()
    {
        return view('livewire.users.user-list', [
            'users' => $this->rows,
        ]);
    }
}
