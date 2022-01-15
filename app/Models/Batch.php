<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batchs';

    protected $fillable = ['camp_id', 'name', 'admission_suffix', 'batch_start', 'batch_end', 'is_appliable', 'is_late_registration_end', 'late_registration_end', 'locationName', 'location', 'check_in_day', 'tel'];

    public function camp() {
        return $this->belongsTo(Camp::class);
    }

    public function sign_info($date = null) {
        $relation = $this->hasMany(BatchSignInAvailibility::class, 'batch_id')->orderBy('id', 'desc');
        if ($date) {
            $relation = $relation->where('start', 'like', "%{$date}%")->get();
        }
        return $relation;
    }

    public function canSignNow() {
        return $this->hasOne(BatchSignInAvailibility::class, 'batch_id')
                ->where([['start', '<=', now()], ['end', '>=', now()]])->first();
    }
}
