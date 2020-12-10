<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getPermission($all = false, $camp_id = null, $function_id = null) {
        if($all){
            return $this->role_relations()->get();
        }
        if(!$camp_id && !$function_id){
            return $this->role_relations()->first()->role;
        }
    }

    public function role_relations(){
        return $this->hasMany('App\Models\RoleUser');
    }
}
