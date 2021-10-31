<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Str;

class FormFieldApiController extends Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->middleware("checkOrigin");
    }

    public function getBatch(Request $request) {
        $batches = \App\Models\Batch::where('camp_id', \App\Models\Batch::find($request->id)->camp->id)->get();
        foreach($batches as &$batch) {
            $batch->name = $batch->name . " "  . (new \Carbon\Carbon($batch->batch_start))->translatedFormat("m/d(D)") . "~" . (new \Carbon\Carbon($batch->batch_end))->translatedFormat("m/d(D)");
        }
        return $batches->toArray();
    }

    public function getFieldData(Request $request)
    {
        $field = Str::snake($request->field);
        if(!$request->camp) {
            $value = Applicant::select($field)->find($request->id)->$field;
            return $value;
        }
        else{
            $model = "\\App\\Models\\" . Str::studly($request->camp);
            $value = $model::select($field)->whereApplicantId($request->id)->first()->$field;
            return $value;
        }
        return abort(500);
    }
}
