<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\BatchSignInAvailibility;
use App\Models\SignInSignOut;
use App\Imports\ApplicantsImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;

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
                "timeslot_name" => $request->timeslot_name,
                "start" => \Carbon\Carbon::parse($request->start),
                "end" => \Carbon\Carbon::parse($end),
                "type" => $request->type
            ]);
            \Session::flash('message', "設定成功。");
            return redirect()->back();
        } catch (\Exception $e) {
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
    public function update($request, $id)
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

    public function sign_upload(Request $request)
    {
        //
        $camp_id = $request->camp_id;
        return view('backend.in_camp.signUpload', compact('camp_id'));
    }
    public function sign_update(Request $request)
    {
        //availability records
        $camp_id = $request->camp_id;
        $camp = Camp::find($request->camp_id);
        $signAvailabilities = $camp->allSignAvailabilities;
        $avail_ids = $signAvailabilities->pluck('id');
        //sign_in_sign_out records
        $signRecords = SignInSignOut::whereIn('availability_id', $avail_ids)->orderBy("applicant_id")->get();

        //imported information, all sheets
        $allsheets = Excel::toCollection(new ApplicantsImport(), $request->fn_sign_update);
        $sheet = $allsheets[0]; //1st sheet
        $titles = $sheet[0];    //title row
        $numrows = $sheet->count();
        $numcols = $titles->count();

        //to identify
        //(1) which column is the applicant_id
        //(2) where the sign_in_sign_out columns start
        $idxsign_start = 0;
        $idxid = 0;
        $idx = 0;
        foreach ($titles as $title) {
            if (str_contains($title, '報名序號')) {
                $idxid = $idx;
            }
            if (str_contains($title, '簽到時間') || str_contains($title, '簽退時間')) {
                $idxsign_start = $idx;
                break;
            }
            $idx++;
        }
        $num_record_add = 0;
        $num_record_delete = 0;
        try {
            for ($idxrow = 1; $idxrow < $numrows; $idxrow++) {
                $row = $sheet[$idxrow];
                $appl_id = $row[$idxid];
                for ($idxsign = $idxsign_start; $idxsign < $numcols; $idxsign++) {
                    $avail_id = $avail_ids[$idxsign - $idxsign_start];    //should be the same order
                    $attendornot = $row[$idxsign];
                    $filtered = $signRecords->where('applicant_id', $appl_id)
                        ->where('availability_id', $avail_id)->first();
                    if ($attendornot == '❌' && !is_null($filtered)) {
                        $filtered->delete();
                        $num_record_delete++;
                    } elseif ($attendornot == '⭕' && is_null($filtered)) {
                        $signadd = new SignInSignOut();
                        $signadd->applicant_id = $appl_id;
                        $signadd->availability_id = $avail_id;
                        $signadd->save();
                        $num_record_add++;
                    }
                }
            }
        } catch(\Exception $e) {
            \logger($e->getMessage());
            $message = "資料庫寫入錯誤";
            return view('backend.in_camp.signUpload', compact('camp_id', 'message', 'num_record_add', 'num_record_delete'));
        }
        $message = "資料庫寫入成功";
        return view('backend.in_camp.signUpload', compact('camp_id', 'message', 'num_record_add', 'num_record_delete'));
    }
}
