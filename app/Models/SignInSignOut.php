<?php

namespace App\Models;

use Applicants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignInSignOut extends Model
{
    use SoftDeletes;

    //
    protected $fillable = ["applicant_id", "availability_id"];

    public $resourceNameInMandarin = '學員 / 義工簽到簽退資料';

    protected $table = "sign_in_sign_out";

    public function applicant() {
        return $this->belongsTo(Applicants::class);
    }

    public function referencedAvailability() {
        return $this->belongsTo(BatchSignInAvailibility::class, 'availability_id', 'id');
    }

    public function referenced() {
        return $this->referencedAvailability();
    }
}
