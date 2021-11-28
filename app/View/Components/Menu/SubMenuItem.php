<?php

namespace App\View\Components\Menu;

use Illuminate\View\Component;

class SubMenuItem extends Component
{
    public $route, $title, $permission;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "#", $title, $permission = "")
    {
        $this->route = $route;
        $this->title = $title;
        $this->permission = $permission;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu.sub-menu-item');
    }
}
