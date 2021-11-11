<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Index extends Component
{

    public $model, $head, $body;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $head, $body)
    {
        $this->model = $model;
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.index')->layout('layouts.main');
    }
}
