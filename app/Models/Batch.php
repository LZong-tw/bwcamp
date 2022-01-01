<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batchs';

    protected $fillable = ['camp_id', 'name', 'admission_suffix', 'batch_start', 'batch_end', 'is_appliable', 'is_late_registration_end', 'late_registration_end', 'locationName', 'location', 'check_in_day', 'tel'];

    public function camp() {
        return $this->belongsTo('App\Models\Camp');
    }

    public function sign_info() {
        # code...
    }
}
