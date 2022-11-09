<?php

namespace App\View\Components\Checkbox;

use Illuminate\View\Component;

class PositionGroups extends Component
{
    public $is_care;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($is_care)
    {
        //
        $this->is_care = $is_care;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.checkbox.position-groups');
    }
}
