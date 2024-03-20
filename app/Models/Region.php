<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public function camps() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Camp::class, 'region_camp_xref', 'region_id', 'camp_id');
    }

    public function applicants() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function roles() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampOrg::class);
    }
}
