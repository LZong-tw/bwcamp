<?
namespace App\Services;

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
}