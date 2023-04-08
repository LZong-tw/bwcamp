<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarerApplicantXref extends Model
{
    //
    public $resourceNameInMandarin = '關懷員';

    public $description = '就是「關懷員」與「學員」的連結，這一個資源將會影響有這些權限的人是否可以指派別人為某學員的關懷員、查詢關懷員名單 / 資料、修改關懷員身分等等。';

    protected $fillable = [
        'applicant_id',
        'user_id'
    ];


}
