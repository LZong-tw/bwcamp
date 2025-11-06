<?php

namespace App\Services;

class MailService
{
    public function send()
    {
        switch ($applicant->gender) {
            case "M":
                $applicant->gender = "男";
                break;
            case "F":
                $applicant->gender = "女";
                break;
            case "NC":
                $applicant->gender = "非常規";
                break;
            default:
                $applicant->gender = "不提供";
                break;
        }
        return $applicant;
    }
}
