<?php

namespace App\View\Components\Button;

use App\Models\Batch;
use Illuminate\View\Component;

class Options extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public bool $isShowVolunteers = false,
        public bool $isShowLearners = false,
        public Batch|null $currentBatch = null,
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
