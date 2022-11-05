<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class ApplicantList extends Component
{
    public $columns;
    public $applucants;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($columns, $applicants)
    {
        //
        $this->columns = $columns;
        $this->applicants = $applicants;
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
