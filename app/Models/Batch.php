<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Batch extends Model
{
    protected $table = 'batchs';

    public $resourceNameInMandarin = '梯次';

    public $resourceDescriptionInMandarin = '營隊中的梯次。';

    protected $fillable = ['camp_id', 'name', 'admission_suffix', 'batch_start', 'batch_end', 'is_appliable', 'is_late_registration_end', 'late_registration_end', 'locationName', 'location', 'check_in_day', 'tel', 'num_groups'];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ApplicantsGroup::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function sign_info($date = null)
    {
        $relation = $this->hasMany(BatchSignInAvailibility::class, 'batch_id')->orderBy('start', 'asc');
        if ($date) {
            $relation = $relation->where('start', 'like', "%{$date}%")->get();
        }
        return $relation;
    }

    public function canSignNow()
    {
        return $this->hasOne(BatchSignInAvailibility::class, 'batch_id')
                ->where([['start', '<=', now()], ['end', '>=', now()]])->first();
    }

    public function dynamic_stats(): MorphMany
    {
        return $this->morphMany(DynamicStat::class, 'urltable');
    }

    public function vbatch(): HasOneThrough
    {
        //foreign key of BatchVbatchXref (batch_id)
        //foreign key of Vbatch (id)
        //local key of Batch (id)
        //local key of BatchVbatchXref (vbatch_id)

        //batch's vbatch
        return $this->hasOneThrough(Vbatch::class, BatchVbatchXref::class, 'batch_id', 'id', 'id', 'vbatch_id');
    }

    public function is_vbatch(): bool
    {
        //the batch is vbatch if it belongs to a vcamp
        return ($this->camp->is_vcamp());
    }
}
