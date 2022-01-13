<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Applicant;

class SignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("sign.home");
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

    public function search(Request $request)
    {
        # code...
        $group = substr($request->admitted_no, 0, 3);
        $number = substr($request->admitted_no, 3, 2);
        $applicant = Applicant::where('is_admitted', 1)->where('is_attend', 1)
            ->where(function($query) use ($request){
                $query->where('id', $request->query_str)
                ->orWhere('name', 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '-', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '(', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, ')', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '（', '')"), 'like', '%' . $request->query_str . '%')
                ->orWhere(\DB::raw("replace(mobile, '）', '')"), 'like', '%' . $request->query_str . '%');
            })
            ->where([['group', $group], ['number', $number]])
            ->orderBy('id', 'desc')->first();
        if (!$applicant) {
            return back()->withErrors('查無報名資料，請重新輸入或與輔導員回報');
        }
        $message = null;
        if ($applicant->batch->canSignNow()) {
            $message = [
                'status' => true,
                'message' => '可報到時間：' . Carbon::parse($applicant->batch->canSignNow()->start)->format('H:i') . ' ~ ' . Carbon::parse($applicant->batch->canSignNow()->end)->format('H:i')
            ];
        } else {
            $message = [
                'status' => false,
                'message' => '目前非報到時間，請稍後再試'
            ];
        }
        $request->flash();
        return view('sign.home', compact('applicant', 'message'));
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
