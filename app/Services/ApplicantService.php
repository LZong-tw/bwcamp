<?
namespace App\Services;

use App\Models\Applicant;

class ApplicantService
{
    public function Mandarization($applicant){
        switch($applicant->gender){
            case "M":
                $applicant->gender = "男";
                break;
            case "F":
                $applicant->gender = "女";
                break;
        }
        return $applicant;
    }

    public function groupAndNumberSeperator($admittedSN){
        $group = substr($admittedSN, 0, 3);
        $number = substr($admittedSN, 3, strlen($admittedSN));
        return compact('group', 'number');
    }

    public function fetchApplicantData($table, $id, $group, $number){
        return Applicant::select('applicants.*')
            ->join($table, 'applicants.id', '=', $table . '.applicant_id')
            ->where('applicants.id', $id)
            ->orWhere(function ($query) use ($group, $number) {
                $query->where('group', 'like', $group);
                $query->where('number', 'like', $number);
            })->first();
    }

    // todo: 錄取後，自動生成轉帳資料的 function
}