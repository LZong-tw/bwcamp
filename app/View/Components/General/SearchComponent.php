<?php

namespace App\View\Components\General;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Camp;

class SearchComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public array $columns,
        public Camp|null $camp,
        public Collection|null $groups,
        public Batch|null  $currentBatch = null,
        public string|null $queryStr = null,
        public bool|null $isCareV = null,
        public bool|null $isShowVolunteers = false,
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
