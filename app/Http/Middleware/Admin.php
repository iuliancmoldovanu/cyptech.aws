<?php

	 namespace App\Http\Middleware;

	 use Closure;
	 use Illuminate\Support\Facades\Auth;
     use Illuminate\Support\Facades\Redirect;

     class Admin{

			public function handle($request, Closure $next){
                if(Auth::user() === null){
                    return Redirect::to('login');
                }
                if (Auth::user()->levelAccess() < 2) {
                    return abort(401);
                }

				 return $next($request);
			}

	 }