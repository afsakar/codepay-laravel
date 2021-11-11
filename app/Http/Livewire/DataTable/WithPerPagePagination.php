<?php

namespace App\Http\Livewire\DataTable;

use Livewire\WithPagination;

/**
 * 
 */
trait WithPerPagePagination
{
    use WithPagination;
    public $perPage = 5;

    public function initializeWithPerPagePagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }   
    
}
