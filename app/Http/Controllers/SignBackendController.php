<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class SignBackendController extends BackendController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $batches = $this->campFullData->batchs;
        foreach($batches as &$batch) {
            $daysIterator = new \DatePeriod(
                new \DateTime($batch->batch_start),
                new \DateInterval('P1D'),
                \Carbon\Carbon::parse($batch->batch_end)->addDay()
            );
    
            $days = [];
            foreach ($daysIterator as $date) {
                $days[] = $date->format('Y-m-d');
            }
            $batch->days = $days;
        }
        
        $instant = false;

        if(!in_array(now()->format('Y-m-d'), $days)) {
            $instant = true;
        }

        return view("backend.in_camp.signSetting", compact("batches", "instant"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
