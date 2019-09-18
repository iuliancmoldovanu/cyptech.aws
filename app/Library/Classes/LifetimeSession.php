<?php
/**
 * Created by PhpStorm.
 * User: Iulian
 * Date: 22/05/2019
 * Time: 20:56
 */

namespace App\Library\Classes;


class LifetimeSession{
    public static function getExpireInSec(){
        return env("SESSION_LIFETIME")*60*1000;
    }

}