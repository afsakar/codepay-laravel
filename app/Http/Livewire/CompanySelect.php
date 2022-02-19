<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class CompanySelect extends Component
{
    public $companies;
    public $isDisabled = false;
    public $header = 'Select Company';

    public $selectedCompany = null;

    public function rules()
    {
        return [
            'selectedCompany' => 'required',
        ];
    }

    public function mount()
    {
        $this->error_message = "";
        $this->companies = Company::where('status', 'active')->get();
        if (empty($this->selectedCompany)) {
            $this->isDisabled = true;
        }
    }

    public function updatedSelectedCompany()
    {
        if($this->selectedCompany != null) {
            $this->isDisabled = false;
        } else {
            $this->isDisabled = true;
        }
    }

    public function contiune()
    {
        $this->validate();
        session()->put('company_id', $this->selectedCompany);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.company-select')->layout('layouts.company-select');
    }
}
