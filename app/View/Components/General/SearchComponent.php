<?php

namespace App\View\Components\General;

use Illuminate\View\Component;

class SearchComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public array $columns,
    ) { }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.general.search-component');
    }
}
