<?php

namespace App\Models;

use Applicants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignInSignOut extends Model
{
    use SoftDeletes;

    //
    protected $fillable = ["applicant_id", "type"];

    protected $table = "sign_in_sign_out";

    public function applicant() {
        return $this->belongsTo(Applicants::class);
    }
}
