<?php
	namespace App;

	use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Model;
    use Exception;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class Player extends Model
	{
		protected $fillable = [
			'user_id', 'skill_level', 'status', 'active'
		];

		public function user()
		{
			return $this->belongsTo('App\User');
		}

		public function games(){
			return $this->belongsToMany(Game::class)->withPivot('team');;
		}

		public static function getPlayerPermission ()
		{
			foreach ( WeekDay::getWeekDays () as $restrictedDay )
			{
				if ( $restrictedDay->day == date ( 'l' ) )
				{
					// select current day row
					if ( $restrictedDay->day_match && date ( 'H:i' ) >= substr ( $restrictedDay->last_confirm_time, 0, 5 ) )
					{
						// dead line has passed
						return 'block'; // block all players
					} else if ( !Game::getCurrentWeekGame ()->result && Game::getCurrentWeekGame ()->generated_by )
					{
						// game finished, needs updated
						return 'updating'; // block all players
					} else
					{
						// no one is restricted
						return 'free';
					}
				}
			}
		}

		public static function getDayNoChanges ()
		{
			foreach ( WeekDay::getWeekDays () as $restrictedDay )
			{
				if ( $restrictedDay->last_chance && date ( 'l' ) == $restrictedDay->day )
				{
					// free for all and no changes can be made
					return true;
				}
			}
			return false;
		}

		public static function startConfirm ()
		{

            $noOfDays = "-".Auth::user()->player->ranking_priority;

		    // get the date of the match day
            $matchDate = date('Y-m-d', strtotime(WeekDay::getPlayingDay()['day']));

            // get the date before match day that relates to $noOfDays
            $confirmDate = date('Y-m-d', strtotime($matchDate . $noOfDays . ' day'));

            // get today date
            $todayDate = date('Y-m-d');

            // when today date gets bigger or equal than the number of days set up return true
            $isConfirmDay = $confirmDate <= $todayDate;
            if($matchDate === $todayDate){ // if is today game
                // if current time is bigger than time match day
                if(WeekDay::getPlayingDay()->start_time <= date('H:i')){
                    $isConfirmDay = false;
                }
            }
            // day of starting confirmation
            $confirmDay = date( "l", strtotime($confirmDate));

			return json_encode([
                'date' => $confirmDate . " 00:00",
                'day' => $confirmDay,
                'isConfirmDay' => $isConfirmDay
            ]);
		}

		public static function isPlayersNeeded ()
		{
			// return the number of players needed
			return ( Game::getCurrentWeekGame ()->players_a_side * 2 ) - count ( self::getPlayersOn () );
		}

		public static function alertMessage ()
		{
			$collection = collect ( [
				'alertStyle', // => 'box style',
				'imgSrc', // => 'image source',
				'message', // => 'text display',
				'showButton' // => 'display button - boolean'
			] );
			if ( Player::getPlayerPermission () == 'updating' )
			{
				return $collection->combine ( [ 'warning', \URL::to ( 'img/tooltips/Warning.png' ), 'Updating in progress', false ] );
			}
			// format text if players are needed
			$isPlayersNeededText = 'Error. ';
			if ( self::isPlayersNeeded () == 1 )
			{
				$isPlayersNeededText = self::isPlayersNeeded () . ' player needed';
			} elseif ( self::isPlayersNeeded () > 1 )
			{
				$isPlayersNeededText = self::isPlayersNeeded () . ' players needed';
			}
			if ( self::getPlayerPermission () == 'free' )
			{
				if ( self::isPlayersNeeded () )
				{
					// show success message: need more players
					return $collection->combine ( [ 'warning', \URL::to ( 'img/tooltips/Warning.png' ), $isPlayersNeededText, false ] );
				} else
				{
				    $getMatchDay = WeekDay::getPlayingDay ()->day;
                    if(WeekDay::getPlayingDay ()->day === date('l')){
                        $getMatchDay = ' today';
                    }
					// show success message: game is ready
					return $collection->combine ( [ 'success', \URL::to ( 'img/tooltips/smile.png' ), 'Teams can be generated ' . $getMatchDay . ' after ' . WeekDay::getPlayingDay ()->last_confirm_time, false ] );
				}
			} elseif ( self::getPlayerPermission () == 'block' )
			{
				if ( self::isPlayersNeeded () )
				{
					if ( self::isPlayersNeeded () < 0 )
					{
						// show danger message: to many players
						return $collection->combine ( [ 'danger', \URL::to ( 'img/tooltips/Critical.png' ), 'Error. To many players available', false ] );
					}
					// show danger message: need more players
					return $collection->combine ( [ 'danger', \URL::to ( 'img/tooltips/Critical.png' ), 'Time expired. ' . $isPlayersNeededText, false ] );
				} elseif ( Game::getCurrentWeekGame()->generated_by == 0 )
				{
					if ( Player::where ( 'user_id', Auth::user ()->id )->first ()->status == 'available' )
					{
						// show message: button to generate teams
						return $collection->combine ( [ 'success', \URL::to ( 'img/tooltips/smile.png' ), 'Ready to get the teams', true ] );
					} else
					{
						// show message: player not in game this week
						return $collection->combine ( [ 'warning', \URL::to ( 'img/tooltips/Warning.png' ), 'Be ready for next week', false ] );
					}
				} else
				{
					if ( Player::where ( 'user_id', Auth::user ()->id )->first ()->status == 'available' )
					{
						// show success message: game is ready
						return $collection->combine ( [ 'success', \URL::to ( 'img/tooltips/smile.png' ), 'Teams available', false ] );
					} else
					{
						// show message: player not in game this week
						return $collection->combine ( [ 'warning', \URL::to ( 'img/tooltips/Warning.png' ), 'Be ready for next week.', false ] );
					}
				}
			} else
			{
				// testing to check for more options
				return $collection->combine ( [ 'warning', \URL::to ( 'img/tooltips/Info.png' ), 'More option to come.', false ] );
			}
		}

		public static function getPlayersOn ()
		{
			$players = \DB::table ( 'players' )->where ( 'status', 'available' )->get ();
			return $players;
		}

		public static function getPlayersAvailable ()
		{
			$players = Player::where ( 'status', 'available' );
			return $players;
		}

		/**
		 * @return static nested arrays with current player logged in include personal details
		 */
		public static function getPlayerFullDetails ()
		{
			foreach ( self::getPlayersFullDetails () as $player )
			{
				if ( $player->user_id == Auth::user ()->id )
				{
					return $player;
				}
			}
		}

		/**
		 * @return static nested arrays with all players include personal details
		 */
		public static function getPlayersFullDetails ()
		{
			// get all players in order of total games played ....
			$players = self::with ( 'user' )
                ->whereHas("user", function($query) {
                    $query->where('is_temp', 'N');
                })
                ->get()
                ->sortByDesc (
			    function ( $player ) {
                    return [ $player[ 'total_games' ], $player[ 'games_won' ], $player[ 'games_draw' ] ];
                } );


			$count_available = 0; // set counter for players already in the game
			$count_waiting = 0; // set counter for players to get in the game
			$count_unavailable = 0; // set counter for players unavailable for current game
			foreach ( $players as $player )
			{
				// all players are waiting for update
				if ( self::getPlayerPermission () === 'updating' )
				{
					// get the players that are waiting to get into the game
					$player->orderList = ++$count_waiting; // counting the players waiting to get into game
					$player->order = 1; // show second group of players in the list
					$player->title_icon = 'muted'; // set color of icon
					$player->title = 'Game complete'; // set the title next to the icon
					$player->body_icon = 'smile-o'; // set the icon for body
					$player->body = 'Updating soon';  // set the text for body
				} else
				{
					// get the players that are already in the game
					if ( $player->status == 'available' )
					{
						$player->title_icon = 'green'; // set color of icon
						$player->title = 'Already confirmed'; // set the title next to the icon
						$player->body_icon = 'smile-o'; // set the icon for body
						$player->body = 'Your place is reserved.'; // set the text for body
						$player->orderList = ++$count_available; // counting the players are in the game
						$player->order = 1; // show first group of players in the list
					}
					// get the players that are unavailable for current game
					if ( $player->status == 'unavailable' )
					{
						$player->title_icon = 'red'; // set color of icon
						$player->title = 'Unavailable'; // set the title next to the icon
						$player->body_icon = 'frown-o'; // set the icon for body
						$player->body = 'Unavailable for ' . $player->unavailable_for; // set the text for body
						$player->orderList = ++$count_unavailable; // counting the players unavailable
						$player->order = 3; // show third group of players in the list
						// check to see if there are players needed
						if ( Player::isPlayersNeeded () && WeekDay::getPlayingDay ()->day == date ( 'l' ) )
						{
							$player->isBtnAvailable = true; // display available button
						} else
						{
							if ( Player::isPlayersNeeded () )
							{
								// message at the bottom of info player
								$player->hasMessage = false;  // display message
								$player->privateMessage = "";//'Try ' . WeekDay::getPlayingDay ()->day . ' if you want to come'; // set text for the message
							} else
							{
								// message at the bottom of info player
								$player->hasMessage = true;  // display message
								$player->privateMessage = 'No places left'; // set text for the message
							}
						}
					}
					// all players are able to confirm
					if ( Player::getPlayerPermission () == 'free' )
					{
						// get the players that are waiting to get into the game
						if ( $player->status == 'waiting' )
						{
							$player->orderList = ++$count_waiting; // counting the players waiting to get into game
							$player->order = 2; // show second group of players in the list
							// check to see if there are players needed
							if ( Player::isPlayersNeeded () )
							{
								$player->title_icon = 'yellow'; // set color of icon
								$player->title = 'Waiting List'; // set the title next to the icon
								$player->body_icon = 'fa-spinner fa-spin'; // set the icon for body
								$player->body = 'Confirm until ' . WeekDay::getPlayingDay ()->day . ' by ' . WeekDay::getPlayingDay ()->last_confirm_time;  // set the text for body
								// buttons at the bottom of info player
								$player->isBtnAvailable = true; // display available button
								$player->isBtnUnavailable = true; // display unavailable button
							} else
							{
								$player->title_icon = 'muted'; // set color of icon
								$player->title = 'Week missed'; // set the title next to the icon
								$player->body_icon = 'frown-o'; // set the icon for body
								$player->body = 'No places left'; // set the text for body
								// message at the bottom of info player
								$player->hasMessage = true;  // display message
								$player->privateMessage = 'Try next week.'; // set text for the message
							}
						}
					}
					// no one will be able to confirm from here
					if ( Player::getPlayerPermission () == 'block' )
					{
						// get the players that haven't confirmed yet
						if ( $player->status == 'waiting' )
						{
							$player->title_icon = 'muted'; // set color of icon
							$player->title = 'To late to confirm'; // set the title next to the icon
							$player->body_icon = 'frown-o'; // set the icon for body
							$player->body = 'The time limit was reached'; // set the text for body
							$player->orderList = ++$count_waiting;  // counting the players who didn't confirm
							$player->order = 2; // show second group of players in the list
						}
						// check to see if there are players needed
						if ( self::isPlayersNeeded () )
						{
							$player->body = 'Players needed'; // set the text for body
							// message at the bottom of info player
							$player->hasMessage = true;
							$player->privateMessage = 'Players needed';
						} else
						{
							// message at the bottom of info player
							$player->hasMessage = false;  // display message
							$player->privateMessage = 'Full of players'; // set text for the message
						}
					}
				}
			}
			return $players->sortBy ( function ( $player )
			{
				return [ $player[ 'order' ], $player[ 'orderList' ] ];
			} );
		}

		public static function getActivePlayersRank ()
		{
			$players = Player::with ( 'user' )->get ()->sortByDesc ( function ( $player )
			{
				return [ $player[ 'total_games' ], $player[ 'games_won' ], $player[ 'games_draw' ] ];
			} );
			$listOrder = 0;
			foreach ( $players as $player )
			{
				$player->listOrder = ++$listOrder;
			}
			return $players;
		}

		/*
		* setup the players into the database for new week
		* example on how to access reset players:
		* $this::resetPlayers();
		*/
		public static function resetPlayers ()
		{
			$players = self::whereIn( 'status', ['available', 'unavailable'])->select('id', 'unavailable_for', 'status', 'current_team', 'total_games')->get();

			foreach ( $players as $player )
			{
                if ( $player->status == 'available' )
                {
                    if(Game::getCurrentWeekGame()->generated_by){
                        $player->total_games++;
                    }
                    $player->status = 'waiting';
                    $player->current_team = null;
                }else{
                    // player unavailable
                    $player->unavailable_for = (int) substr ( $player->unavailable_for, 0, 1 );

                    $player->unavailable_for--;

                    $player->status = 'waiting';
                    if($player->unavailable_for){
                        $player->unavailable_for .= $player->unavailable_for === 1 ? ' week' : ' weeks';
                        $player->status = 'unavailable';
                    }
                }
                $player->save();
			}
		}

		/**
		 * @param $table_name
		 * @param $fieldName
		 * @return array (enum from table)
		 */
		public static function getPossibleRoles ( $table_name, $fieldName )
		{
			$type = \DB::select ( \DB::raw ( "SHOW COLUMNS FROM $table_name WHERE Field = '$fieldName'" ) )[ 0 ]->Type;
			preg_match ( '/^enum\((.*)\)$/', $type, $matches );
			$enum = array ();
			foreach ( explode ( ',', $matches[ 1 ] ) as $value )
			{
				$v = trim ( $value, "'" );
				$enum = array_add ( $enum, $v, $v );
			}
			return $enum;
		}

		public function scopeGetPlayersList(){
			$users = User::with('player')
				->join('players', 'players.user_id', '=', 'users.id')
				->orderBy('players.last_confirm', 'desc')
				->get();

			$numbering = 1;
			foreach ($users as $key => $user)
			{
				$user->username = $user->username . ' (' . $user->name . ')';
				$user->numbering = '<strong>' . $numbering . '.</strong>';
				$no_not_available = ($user->unavailable_for > 0) ? ' (' . $user->unavailable_for . ')' : '';
				$user->status = $user->status . '' . $no_not_available;
				$user->btns = '<btn class="btn btn-primary btn-xs margin-r-5" title="Edit" onclick="window.location=\'users/update/'.$user->id.'\'">';
				$user->btns .= '<i class="fa fa-pencil-square-o"></i> Edit</btn>';
				if ( $user->active ){
					$user->btns .= '<btn class="btn btn-danger btn-xs" title = "Disable" onclick = "playerStatus(\'disable\', \''.$user->id.'\')" >';
					$user->btns .= '<i class="fa fa-trash-o" ></i> Disable</btn >';
				}else{
					$user->btns .= '<btn class="btn btn-success btn-xs" title = "Active" onclick = "playerStatus(\'active\', \''.$user->id.'\')" >';
					$user->btns .= '<i class="fa fa-plus-square" ></i> Active</btn >';
				}
				$numbering++;
			}
			echo json_encode( $users, JSON_NUMERIC_CHECK );
		}

		public static function getLastAccess(){
            $last_access = Session::get("last_access");

		    if(isset($last_access)){
                return $last_access;
            }

            $User = Auth::user();
            if(is_null($User)){
                return Carbon::now()->format('D, jS \\of F Y H:i');
            }

            if($User->total_login === 0){
                return Carbon::now()->format('D, jS \\of F Y H:i');
            }

            try{
                Session::put("last_access", $User->last_access_at);
                $User->last_access_at = Carbon::now();
                $User->save();
                return Session::get("last_access");
            }catch (Exception $e){
                return Carbon::now()->format('D, jS \\of F Y H:i');
            }
        }

        /**
         * @param $availablePlayers
         * @param $set_generated
         */
        public static function updateActive( $availablePlayers, $set_generated): void
        {
            $uPlayers = [];
            foreach ($availablePlayers as $u) {

                $uPlayers[] = [
                    "game_id" => $set_generated->id,
                    "player_id" => $u->id,
                    "team" => $u->current_team,
                ];

                if ($set_generated->generated_by > 0) {
                    if ($set_generated->result === 'draw') {
                        $u->games_draw += 1;
                        $u->points += 1;
                    }else{
                        if ($u->current_team === $set_generated->result) {
                            $u->games_won += 1;
                            $u->points += 3;
                        }else{
                            $u->games_lost += 1;
                        }
                    }

                    $u->total_games += 1;
                    $u->status = 'waiting';
                    $u->current_team = null;
                }
                $u->save();
            }
            \DB::table('game_player')->insert($uPlayers); // Query Builder approach
        }


        /**
         * @param $unavailablePlayers
         */
        public static function updateUnavailable($unavailablePlayers): void
        {
            foreach ($unavailablePlayers as $u) {
                $unavailableFor = (int) $u->unavailable_for - 1;
                if (!$unavailableFor) {
                    $u->status = 'waiting';
                }
                $u->unavailable_for = $unavailableFor
                    ? ($unavailableFor === 1
                        ? '1 week'
                        : $unavailableFor . " weeks")
                    : '0';

                $u->save();
            }
        }

	}
