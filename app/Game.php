<?php
	namespace App;

	use App\Library\Classes\GameManager;
    use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;

	/**
	 * Class Game
	 * @package App
	 *
	 * fields available
	 *       ->team_green        (null until game complete) - names of players
	 *       ->team_red          (null until game complete) - names of players
	 *       ->result            (0 until game complete) - red, green or draw
	 *       ->generated_by      (0 until teams will be generated) - id of player that has created the team
	 *       ->week_number       (get week number of that year) - 1 to 52
	 *       ->players_a_side    (number of player on one side) - 5,6,7,...
	 *       ->restriction       (many choices) - free, by_active, by_rating, by_oldest(date registered)
	 *       ->current           (0 until game complete) - 0 or 1
	 *       ->created_at        (date when game start to register the players) - timestamp
	 *       ->updated_at        (date when game gets changes) - timestamp
	 *       ->starts_at         (date when game starts) - timestamp
	 */
	class Game extends Model
	{
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'team_green', 'team_red', 'result', 'generated_by', 'week_number', 'players_a_side', 'restriction', 'current', 'starts_at'
        ];
		
		public function players(){
			return $this->belongsToMany(Player::class)->withPivot('team');
		}

		private static $generateTeamsCounter = 0;
		private static $teamDifference = 1;
		
		/**
		 * @return mixed - multidimensional array
		 * note: newest game row added come first
		 */
		public static function getGames ()
		{
			$games = self::orderBy ( 'updated_at', 'desc' )->orderBy('week_number', 'desc');
			return $games;
		}

        /**
         *teams will be generated by accessing this method
         */
		public static function generateTeams ()
		{
			// don't allow to generate the teams outside time period by accessing the url in the browser
			if ( self::getCurrentWeekGame()->generated_by != 0 )
			{
                \Log::error("Team can't be generate, already done by id " . self::getCurrentWeekGame()->generated_by);
                return;
			}
			// get all players available for the current week in random (Eloquent\Collection)
			$playersAvailableRandom = Player::getPlayersAvailable ()->inRandomOrder ()->with(['user'])->get ();
			$players = [
			    "green" => [
                    "ids" => [],
                    "username" => [],
                    "goalkeeper" => 0,
                    "points" => 0
                ],
			    "red" => [
			        "ids" => [],
			        "username" => [],
                    "goalkeeper" => 0,
                    "points" => 0
                ]

            ]; // store total points for green
            // get current game that has not teams generated
            $last_game = self::getCurrentWeekGame ();

			foreach ( $playersAvailableRandom as $key => $player ){

			    if(count($players["green"]["ids"]) < $last_game->players_a_side){
                    $players["green"]["ids"][] = $player->id;
                    $players["green"]["username"][] = $player->user->username;
                    $players["green"]["skill"][] = $player->skill_level;
                    $players["green"]["points"] += $player->skill_level;

                    if($player->player_position === 'Goalkeeper'){
                        $players["green"]["goalkeeper"]++;
                    }
                }else{
                    if($player->player_position === 'Goalkeeper'){
                        $players["red"]["goalkeeper"]++;
                    }
                    $players["red"]["username"][] = $player->user->username;
                    $players["red"]["points"] += $player->skill_level;
                    $players["red"]["skill"][] = $player->skill_level;
                    $players["red"]["ids"][] = $player->id;
                }

			}
			// get the difference between the teams to make the teams balanced
			$balance_teams = abs ( $players["green"]["points"] - $players["red"]["points"] );
			// get the difference between the goalkeepers just to make sure one team has at least one goalkeeper
			$balance_goalkeepers = abs ( $players["green"]["goalkeeper"] - $players["red"]["goalkeeper"] );
			if(self::$generateTeamsCounter > 100){ // if teams not balanced increase difference
                self::$teamDifference = 2;
                \Log::error("teamDifference static variable increased to 2");
            }


            \Log::info([$players["green"]["points"], $players["red"]["points"], $balance_teams, self::$teamDifference, $balance_goalkeepers]);
			// check if the difference between teams are bigger then 1
			if ( $balance_teams > self::$teamDifference || $balance_goalkeepers > 1 ) {
                self::$generateTeamsCounter++;
				self::generateTeams (); // run this method again
                return;
			}

            \Log::info("Teams generate after " . self::$generateTeamsCounter . " attempts.");

            // update players to them teams
            foreach ( $players as $name => $teams ) {
                Player::whereIn ( 'id', $teams['ids'] )
                    ->update ( [ 'current_team' => $name ] );

                $teamName = 'team_'.$name;
                $last_game->{$teamName} = implode(', ', $players[$name]['username']);
            }

            // get the player who has generated the teams
            $last_game->generated_at = date("Y-m-d H:i:s");
            $last_game->generated_by = Auth::user ()->id;
            $last_game->save ();
		}

		
		/**
         *  Get previous game
		 */
		public function getPreviousGame(){
			return $this->orderBy('id', 'desc')->skip(1)->first();
		}



        /**
         * Get current running game
         * @return mixed- array with all fields of current game still on
         *       note: should be only one running game at a time
         */
        public function scopeGetCurrentWeekGame (){

            $currentGame = $this->where('current', 1)->get();
            if($currentGame->count() === 1){
                return $currentGame->first();
            }
            if($currentGame->count() === 0){
                $this->createGame();
                return $this;
            }
            \Log::error("Too many running games");
            return null;
        }

        public function getGameDate (){
            return Carbon::parse($this->getCurrentWeekGame()->starts_at);
        }

        /**
         *
         */
        public function scopeCreateGame ()
        {
            $gameManager = new GameManager();
            $newGame = new Game();
            $newGame->week_number = date ( 'W' );
            $newGame->restriction = 'free';
            $newGame->current = 1;
            $newGame->status = 'running';
            $newGame->updated_at = Carbon::now()->addMinute();
            $newGame->starts_at = $gameManager->getNextDateGame();
            $newGame->save ();
        }


        /**
         * @param Game $lastGame
         */
        public static function updateActive(Game $lastGame): void
        {
            $gameManager = new GameManager();

            if(!$gameManager->isCurrentGameCancelled()){
                if($lastGame){
                    // update players
                    $players = Player::whereIn ('status', ['available', 'unavailable'])->get()->groupBy('status');

                    Player::updateUnavailable($players['unavailable'] ?? []);
                    Player::updateActive($players['available'] ?? [], $lastGame);
                    // update current game

                    $lastGame->status = $lastGame->generated_by > 0 ? 'completed' : 'canceled';
                    $lastGame->generated_by = $lastGame->generated_by === 0 ? '-1' : $lastGame->generated_by;
                    $lastGame->current = 0;
                    $lastGame->save();
                    \Log::info("The game {$lastGame->status} by " . (auth()->user() ? auth()->user()->username : "CronTab") . " on ".Carbon::now().".");

                    self::createGame();
                }
            }

        }
	}
