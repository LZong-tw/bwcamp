<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchSignInAvailibility;

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
        $instant = false;

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

            foreach ($batch->sign_info as $row) {
                $date = substr($row->start, 0, 10);
                if(!in_array($date, $days)) {
                    $instant = true;
                    array_unshift($days, $date);
                }
            }
            $batch->days = $days;
        }

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
        if($request->end && $request->duration) {
            return redirect()->back()->withErrors(["結束時間或持續時間只能擇一填寫。"])->withInput();
        }
        if(!$request->end && !$request->duration) {
            return redirect()->back()->withErrors(["未填寫任何結束時間。"])->withInput();
        }
        
        $request->start = $request->day . " " . $request->start;

        if($request->end) {
            $end = $request->day . " " . $request->end;
        } else {
            $end = \Carbon\Carbon::parse($request->start)->addMinutes($request->duration);
        }

        try {
            BatchSignInAvailibility::create([
                "batch_id" => $request->batch_id,
                "start" => \Carbon\Carbon::parse($request->start),
                "end" => \Carbon\Carbon::parse($end),
            ]);
            \Session::flash('message', "設定成功。");
            return redirect()->back();
        } 
        catch (\Exception $e) {
            \logger($e->getMessage());
            return redirect()->back()->withErrors(["發生未知錯誤，設定失敗。"])->withInput();
        }
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
    public function destroy($camp_id, $id)
    {
        //
        BatchSignInAvailibility::destroy($id);
        \Session::flash('message', "刪除成功。");
        return redirect()->back();
    }
}
