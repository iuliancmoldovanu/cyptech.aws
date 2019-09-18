<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'role', 'remember_token', 'is_temp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function player(){
        return $this->hasOne('App\Player');
    }

    public function levelAccess(){
        if($this->id === 2){
            return 3;
        }
        switch ($this->role){
            case "player":
                return 1;
                break;
            case "admin":
                return 2;
                break;
            case "master":
                return 3;
                break;
            default:
                return 0;
        }
    }
    
}
