<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\SignInSignOut;
use App\Services\ApplicantService;

class SignController extends Controller
{
    protected $applicantService;

    public function __construct(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

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
        $applicant = Applicant::find($request->applicant_id);
        if($applicant->hasAlreadySigned($request->availability_id)) {
            return "請勿重複簽到/退";
        }
        [, $signInSignOutObject] = $this->applicantService->generatesSignMessage($applicant);
        // 如果現在還在簽到時間內，則抓出學員在簽到時間內的簽到資料
        $isSigned = $applicant->hasSignedThisTime(now());
        try {
            SignInSignOut::create($request->all());
        } catch (\Throwable $th) {
            \Log::error($th);
            return view('sign.home', compact('applicant', 'isSigned'))
                    ->with('signInfo', $signInSignOutObject)
                    ->with("message", [
                        "status" => false,
                        "message" => "發生未預期的錯誤，請關懷員回報問題及目前頁面截圖"
                    ])
                    ->with("name", $applicant->name)
                    ->with("mobile", $applicant->mobile);
        }
        return app()->call(SignController::class . "@search", [
            "request" => $request,
            "applicant" => $applicant
        ]);
    }

    public function search(Request $request, $applicant = null)
    {
        if($applicant) {
            $request->request->add([
                "name" => $applicant->name,
                "mobile" => $applicant->mobile
            ]);
        }
        $applicant = $this->applicantService->retriveApplicantForSignInSignOut($request);
        if (!$applicant || $applicant->deleted_at) {
            return back()->withErrors('查無報名資料，請重新輸入或與關懷員回報');
        }
        [$message, $signInSignOutObject] = $this->applicantService->generatesSignMessage($applicant);
        // 如果現在還在簽到時間內，則抓出學員在簽到時間內的簽到資料
        $isSigned = $applicant->hasSignedThisTime(now());
        $request->flash();
        return view('sign.home', compact('applicant', 'message', 'isSigned'))
                    ->with('signInfo', $signInSignOutObject);
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
