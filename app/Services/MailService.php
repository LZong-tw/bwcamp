<?
namespace App\Services;

class MailService
{
    public function send(){
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