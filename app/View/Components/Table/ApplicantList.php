<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class ApplicantList extends Component
{
    public $columns;
    public $applicants;
    public $is_vcamp;
    public $is_care;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($columns, $applicants, $is_vcamp = false, $is_care = false)
    {
        $this->columns = $columns;
        $this->applicants = $applicants;
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
        return view('components.table.applicant-list');
    }
}
