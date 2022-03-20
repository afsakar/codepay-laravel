<?php

namespace App\Http\Livewire\Companies;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithToastNotification;
use Livewire\WithFileUploads;

class CompanyList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification, WithFileUploads;

    public Company $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $singleDelete = false;
    public $editingModal = false;
    public $logo;

    protected $listeners = [
        'save',
    ];

    public $filters = [
        'search' => "",
    ];

    public function rules()
    {
        return [
            'editing.name' => 'required|min:3|unique:companies,name,'.$this->editing->id,
            'editing.owner' => 'nullable',
            'editing.tc_number' => Rule::requiredIf($this->editing->tax_number == "").'|nullable|numeric|digits:11|unique:companies,tc_number,'.$this->editing->id,
            'editing.tel_number' => 'required|numeric|digits:10',
            'editing.gsm_number' => 'nullable|numeric|digits:10',
            'editing.fax_number' => 'nullable|numeric|digits:10',
            'editing.email' => 'nullable|email',
            'editing.address' => 'required|min:3',
            'editing.tax_office' => 'nullable|min:3',
            'editing.tax_number' => 'nullable|numeric|unique:companies,tax_number,'.$this->editing->id,
            'editing.status' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.name' => __('Name'),
            'editing.owner' => __('Owner'),
            'editing.status' => __('Status'),
            'editing.tc_number' => __('Identification Number'),
            'editing.tel_number' => __('Phone Number'),
            'editing.gsm_number' => __('GSM Number'),
            'editing.fax_number' => __('Fax Number'),
            'editing.email' => __('Email Address'),
            'editing.address' => __('Address'),
            'editing.tax_office' => __('Tax Office'),
            'editing.tax_number' => __('Tax Number'),
            'logo' => __('Logo'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankCompany();
    }

    public function makeBlankCompany()
    {
        return Company::make(['status' => 'active']);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Company $company)
    {
        $this->useCachedRows();
        if($this->editing->isNot($company)) $this->editing = $company;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankCompany();
        $this->editingModal = true;
    }

    public function save()
    {
        $this->validate();
        if(!empty($this->logo)) {
            Storage::delete('public/'.$this->editing['logo']);
            $random = Str::random(10);
            $this->editing['logo'] = 'company-logos/' . $random . '.' . $this->logo->extension();
            $this->logo->storeAs('company-logos/', $random . '.' . $this->logo->extension(), 'public');
        }
        $this->editing->save();
        if($this->createMode) {
            $this->createMode = false;
            $this->notify('Record has been created successfully!');
        }else{
            $this->notify('Record has been updated successfully!');
        }
        $this->editingModal = false;
        $this->logo = null;
    }

    public function close()
    {
        $this->editingModal = false;
        $this->logo = null;
        $this->createMode = false;
        $this->makeBlankCompany();
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

    public function deleteImage(Company $company)
    {
        $this->logo = null;
        if($this->editing->isNot($company)) $this->editing = $company;
        $this->editing['logo'] = null;
    }
    /* Editing / Creating / Deleting / Exporting */

    public function toggleFilters()
    {
        $this->useCachedRows();
    }

    public function toggleSwitch(Company $company)
    {
        $company->status == "active" ? $company->status = "inactive" : $company->status = "active";
        $company->save();
        $this->notify('Company status updated successfully.');
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
        $query = Company::query()
            ->when($this->filters['search'], fn($query, $search) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('owner', 'like', '%'.$search.'%')
                ->orWhere('tc_number', 'like', '%'.$search.'%')
                ->orWhere('tax_number', 'like', '%'.$search.'%'));
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
        return view('livewire.companies.company-list', [
            'companies' => $this->rows
        ]);
    }
}
