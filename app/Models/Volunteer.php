<?php

namespace App\Models;
class Volunteer extends Applicant {
    public $resourceNameInMandarin = '一般義工資料';
    public $resourceDescriptionInMandarin = '義工報名表或詳細資料頁面中的資料。';
    public $table = 'applicants';
}
