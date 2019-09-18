<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    /**
    * Class Week
    * @package App
    *
    * fields available
    *       ->day       	 			(days of a week) - text
    *       ->restriction_apply	(days when only selected players can confirm if true) - boolean
    *       ->last_chance  			(???) - boolean
    *       ->day_match      		(the day when the game is playing) - boolean
    *       ->start_time       	(start time of the game) - time
    *       ->last_confirm_time (time when no one will be able to confirm) - time
    */
    class WeekDay extends Model {

        /**
        * @return mixed
        */
        public static function getWeekDays() {
            return self::all();
        }

        /**
        * Field from the playing day
        * @return array
        */
        public static function getPlayingDay() {
            $dayTimeOfGame = self::where("day_match", 1)->first();
            if ( !is_null($dayTimeOfGame) ){
            $dayTimeOfGame->last_confirm_time = substr ( $dayTimeOfGame->last_confirm_time, 0, 5 );
            return $dayTimeOfGame;
            }else{
            return abort(503);
            }
        }

        public function scopeGetCurrentPlayDay(){
            $currentPlayDay = $this->where("day_match", 1)->get();

            if($currentPlayDay->count() === 1){
                return $currentPlayDay->first();
            }
            return null;
        }
    }

