<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChangeCompany extends Component
{

    public function companyChange()
    {
        session()->pull('company_id');
        return redirect()->route('company.select');
    }

    public function render()
    {
        return view('livewire.change-company');
    }
}
