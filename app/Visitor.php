<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Visitor extends Model
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'ip', 'page', 'accessed_date'
    ];

	public function user() {
		return $this->hasOne('App\User');
	}

    public function createVisitor()
    {
        $this->create([
            "ip" => $_SERVER['REMOTE_ADDR'],
            "username" => Auth::user()->username,
            "accessed_date" => Carbon::now(),
            "page" => $_SERVER['REQUEST_URI']
        ]);
	}
}
