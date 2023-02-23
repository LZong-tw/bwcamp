<?php

namespace App\View\Components\General;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Settings extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public Collection $batches,
        public bool $isShowVolunteers = false,
        public bool $isShowLearners = false,
        public bool $isSettingCarer = false,
        public $carers = null,
        public $targetGroupIds = null,
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
        return view('components.general.settings');
    }
}
