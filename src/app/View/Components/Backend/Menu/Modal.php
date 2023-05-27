<?php

namespace App\View\Components\Backend\Menu;

use App\Models\Categories;
use Illuminate\View\Component;

class Modal extends Component
{
    public $category;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->category = Categories::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components..backend.menu.modal');
    }
}
