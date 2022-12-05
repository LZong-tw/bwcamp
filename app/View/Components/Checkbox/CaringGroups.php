<?php

namespace App\View\Components\Checkbox;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class CaringGroups extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public Collection $batches)
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
        return view('components.checkbox.caring-groups');
    }
}
