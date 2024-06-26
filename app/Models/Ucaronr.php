<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UserCanAccessResourceOrNotResult
 * @package App\Models
 */
class Ucaronr extends Model
{
    //
    protected $table = 'user_can_access_resource_or_not_results';

    protected $fillable = ['user_id', 'camp_id', 'batch_id', 'region_id', 'accessible_id', 'accessible_type', 'can_access'];
}
