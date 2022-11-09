<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

class Options extends Component
{
    public $is_ingroup;
    public $is_vcamp;
    public $is_care;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($is_ingroup = false, $is_vcamp = false, $is_care = false)
    {
        //
        $this->is_ingroup = $is_ingroup;
        $this->is_vcamp = $is_vcamp;
        $this->is_care = $is_care;
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
