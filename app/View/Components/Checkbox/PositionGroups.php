<?php

namespace App\View\Components\Checkbox;

use Illuminate\View\Component;

class PositionGroups extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public bool $isShowLearners)
    {
        //
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
