<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GSheetService;
use App\Models\Applicant;


class SheetController extends Controller
{
    protected $gsheetservice;
    /**
     * 建構子
     */
    public function __construct(GSheetService $gsheetservice)
    {
        $this->gsheetservice = $gsheetservice;
    }

    public function Sheet()
    {
        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        dd($sheets);
    }

    public function AddSheet()
    {
        $data = ['2020-4-19', '3000', '2000', '', 'test'];
        $sheets = $this->gsheetservice->Append(config('google.post_spreadsheet_id'), config('google.post_sheet_id'), $data);
        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));
        dd($sheets);
    }

    public function showGSFeedback(Request $request)
    {
        $applicant = Applicant::find($request->applicant_id);
        $name_tg = $applicant->name;    //target name

        $sheets = $this->gsheetservice->Get(config('google.post_spreadsheet_id'), config('google.post_sheet_id'));

        $titles = $sheets[0];
        $key = array_search('您的姓名', $titles); // $key = 3;
        //$rows = count($sheets);
        $i = 0;
        foreach ($sheets as $row) {
            $names[$i] = $row[$key];
            $i = $i+1;
        }
        $key1 = array_search($name_tg, $names);
        if ($key1 == false)
            $contents = null;
        else
            $contents = $sheets[$key1];

        return view('backend.in_camp.gsFeedback', compact('titles','contents'));
    }
}
