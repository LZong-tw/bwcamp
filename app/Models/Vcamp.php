<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vcamp extends Camp
{
    protected $table = 'camps';
    public function vcamp()
    {
        return '';
    }
}
