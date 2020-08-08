<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function campIndex($camp_id = 1, $batch_id = 1)
    {
        $camp_data = Camp::getCampWithBatch($camp_id, $batch_id);
        dd($camp_data);
        return ;
    }

    public function campRegistration($camp_id = 1, $batch_id = 1)
    {
        $camp_data = Camp::getCampWithBatch($camp_id, $batch_id);
        return view($camp_data->table . '.registration',
            ['camp_id' => $camp_id,
            'batch_id' => $batch_id,
            'camp_data' => $camp_data]);
    }
}
