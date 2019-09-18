<?php
/**
 * Created by PhpStorm.
 * User: Iulian
 * Date: 27/12/2018
 * Time: 16:29
 */

namespace App\Library\Classes;


use App\Game;
use App\WeekDay;
use Carbon\Carbon;

class GameManager{
    const DAYS_CANCEL_A_GAME = 1; // define the total number of days before a game can be cancelled
    private $dayMatch; // get the day when the game starts

    public function __construct(){
        $this->dayMatch = WeekDay::getCurrentPlayDay();
    }

    /**
     * @return bool
     */
    public function isCurrentGameCancelled () : bool {

        $Game = new Game;
        $previousGame = $Game->getPreviousGame();

        if($previousGame !== null && $this->canBeCancelled() && Carbon::now()->diffInDays($Game->getGameDate()) > self::DAYS_CANCEL_A_GAME){
            // if this is true then last game was in the last 2 days
            return $previousGame->status === "canceled";
        }
        return false;
    }

    /**
     * the game can be cancelled
     * @return bool
     */
    public function canBeCancelled () : bool {
        return in_array(Carbon::today()->formatLocalized ( '%A' ), $this->getValidCancellationDays());
    }


    public function getThisDateGame (){
        $date =  new Carbon("this {$this->dayMatch->day}");
        return $date->hour(substr($this->dayMatch->start_time, 0, 2));
    }

    public function getNextDateGame (){

        $date =  new Carbon();

        $t = explode(':', $this->dayMatch->start_time); // get the play time from settings
        $g = Game::all()->last(); // get the last feature from games table


        if(Carbon::parse($g->starts_at)->toDateString() <= $date->toDateString()) {
            /** the last game is in the feature, get the end of this week and set it for following week on the day that is specified */
            return $date
                ->endOfWeek()
                ->modify("next {$this->dayMatch->day}")
                ->setTime($t[0],$t[1])
                ->subMinutes(30);
        }

        /** the last game already in the past set it for next week on the day that is specified */
        return $date
            ->modify("next {$this->dayMatch->day}")
            ->setTime($t[0],$t[1])
            ->subMinutes(30);
    }


    /**
     * get the range of week days when a game can be cancelled
     * @return array
     */
    public function getValidCancellationDays() : array {
        $validDays = [$this->dayMatch->day];

        $carbon =  new Carbon("this {$this->dayMatch->day}");
        for ($i = 0; $i < self::DAYS_CANCEL_A_GAME; $i++) {
            $validDays[] = $carbon->subDay(1)->formatLocalized ( '%A' );
        }

        return array_reverse($validDays,false); // ["Friday", "Saturday"]
    }

}