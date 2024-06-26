<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class UserCanAccessResourceOrNotResult extends Model
{
    use Cachable;
    //
    protected $fillable = ['user_id', 'accessible_id', 'accessible_type', 'can_access'];
}
