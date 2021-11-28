<?php

namespace App\View\Components\Menu;

use Illuminate\View\Component;

class MenuItem extends Component
{

    public $url, $active, $title, $icon, $submenus, $methodFrom, $methodTo, $permission;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($url = "#", $active = "", $title= "", $icon = "", $submenus = [], $methodFrom = "", $methodTo = "", $permission = "")
    {
        $this->url = $url;
        $this->active = $active;
        $this->title = $title;
        $this->icon = $icon;
        $this->submenus = $submenus;
        $this->methodFrom = $methodFrom;
        $this->methodTo = $methodTo;
        $this->permission = $permission;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu.menu-item');
    }
}
