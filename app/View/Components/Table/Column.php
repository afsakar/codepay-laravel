<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Column extends Component
{
    public $sortable;
    public $direction;
    public $multiColumn;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($sortable = null, $direction = null, $multiColumn = null)
    {
        $this->sortable = $sortable;
        $this->direction = $direction;
        $this->multiColumn = $multiColumn;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.column');
    }
}
