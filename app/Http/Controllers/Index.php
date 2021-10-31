<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $campsByBatch = \App\Models\Batch::with(['camp' => function($query){ $query->where('test', 0); }])->where('batch_start', '>', now())->groupBy('camp_id')->get();
        $camps = array();
        foreach($campsByBatch as &$camp){
            if($camp->camp){
                $camp->camp->batch_id = $camp->id;
                $camp->camp->batch_name = $camp->name;
                array_push($camps, $camp->camp);
            }
        }
        $camps = collect($camps);
        return view('welcome', compact('camps'));
    }
}
