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

    public $description = '簽到簽退的權限。擁有這個資源權限的人才可以看、幫別人簽到簽退等。';

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
