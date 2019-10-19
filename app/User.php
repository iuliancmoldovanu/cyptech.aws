<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        'password', 'remember_token',
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
