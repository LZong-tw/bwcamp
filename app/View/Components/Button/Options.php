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
        public bool $isIngroup = false,
        public bool $isVcamp = false,
        public bool $isCare = false,
        public bool $isCareV = false
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
