<?php
/**
 * Created by PhpStorm.
 * User: Iulian
 * Date: 07/06/2018
 * Time: 20:43
 */

namespace App\Helpers;


use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VisitorsBuilder
{
    public static function createVisitor($page = null){

        $page = $page ?? $_SERVER['REQUEST_URI'];
        Visitor::create([
            "ip" => $_SERVER['REMOTE_ADDR'],
            "username" => Auth::user()->username,
            "accessed_date" => Carbon::now(),
            "page" => $page
        ]);

    }
}