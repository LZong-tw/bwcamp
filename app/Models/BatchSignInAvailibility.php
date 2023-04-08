<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchSignInAvailibility extends Model
{
    use SoftDeletes;

    //
    protected $table = "batch_sign_availibilities";

    public $resourceNameInMandarin = '梯次可簽到退資訊';

    public $resourceDescriptionInMandarin = '建立簽到作業所需的權限。擁有新增這個資源權限的人才可以決定什麼時間點可以簽到簽退。';

    protected $fillable = ["batch_id", "start", "end", "type"];

    public function batch() {
        return $this->belongsTo(Batch::class);
    }

    public function camp() {
        return $this->batch()->camp();
    }

    public function applicants() {
        return $this->hasManyThrough(Applicant::class, SignInSignOut::class, "availability_id", "id", "id", "applicant_id");
    }

    public function isSignIn() {
        return $this->type == "in";
    }

    public function signInfo() {
        return $this->type == "out";
    }

    public function isSignableAt($datetime) {
        return $this->where([['start', '<=', $datetime], ['end', '>=', $datetime]])->first();
    }

    public function getStartTimeAttribute() {
        return substr($this->start, 0, 16);
    }

    public function getEndTimeAttribute() {
        return substr($this->end, 0, 16);
    }

    public function getSignTimeAttribute() {
        return $this->getStartTimeAttribute() . " ~ " . substr($this->end, 11, 5);
    }
}
