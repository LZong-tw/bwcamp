<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

class Options extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public bool $is_ingroup = false,
        public bool $is_vcamp = false,
        public bool $is_care = false
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button.options');
    }
}
