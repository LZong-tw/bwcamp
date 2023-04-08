<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    //
    protected $table = 'check_in';

    public $resourceNameInMandarin = '報到';

    public $description = '協助學員完成報到作業的功能。';

    public function applicant() {
        return $this->belongsTo(Applicant::class);
    }
}
