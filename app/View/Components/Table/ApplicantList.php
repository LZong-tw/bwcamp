<?php

namespace App\View\Components\Table;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ApplicantList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public array|null $columns,
        public Collection|null $applicants,
        public bool $isSetting = false,
        public bool $isShowVolunteers = false,
        public bool $isShowLearners = false,
        public Collection|null $registeredVolunteers = null,
        public $isSettingCarer = null
    ) { }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.applicant-list');
    }
}
