<?php
namespace App\Http\Controllers;

class SessionController extends Controller{
    public function reloadSession(){
        $lifetime = env("SESSION_LIFETIME") ?? config('session.lifetime');
        return [
            "lifetime" => (int) $lifetime * 60,
            "session_start_at" => time()
        ];
    }
}