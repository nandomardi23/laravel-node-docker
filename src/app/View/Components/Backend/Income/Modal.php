<?php

namespace App\View\Components\Backend\Income;

use App\Models\TypeIncome;
use Illuminate\View\Component;

class Modal extends Component
{
    public $typeincome;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->typeincome = TypeIncome::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components..backend.income.modal');
    }
}
