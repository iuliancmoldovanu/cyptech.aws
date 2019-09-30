<?php
	namespace App\Http\Controllers;

	use App\Helpers\VisitorsBuilder;
    use App\Library\Classes\GameManager;
    use App\Player;
	use App\Game;
	use App\User;
	use App\WeekDay;
	use Carbon\Carbon;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
	use App\Http\Requests;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Str;
    use Mockery\CountValidator\Exception;
	use Illuminate\Support\Facades\Validator;

	class AdminController extends Controller
	{
		public function __construct ()
		{
			$this->middleware ( 'admin' );
		}

		public function games ()
		{
            VisitorsBuilder::createVisitor();
            $gameManager = new GameManager();
            $cancelDays = $gameManager->getValidCancellationDays();
            $gameDay = array_pop($cancelDays);
            $cancelDays = count($cancelDays) ? implode(", ", $cancelDays) . " &amp; " . $gameDay : $gameDay;

			return view ( 'admin.games', [
                'canBeCancelled' => $gameManager->canBeCancelled(),
                'daysWhenCancelled' => $cancelDays,
                'isCurrentGameCancelled' => $gameManager->isCurrentGameCancelled(),
				'match' => WeekDay::getPlayingDay (),
				'week' => WeekDay::getWeekDays (),
				'player' => Player::getPlayerFullDetails (),
				'games' => Game::getCurrentWeekGame (),
			] );
		}

		public function update_game ( Request $request )
		{
			// check if there are more players or dead time bigger then start time
			if ( $request->players * 2 < Player::getPlayersAvailable ()->count () || $request->time_confirm >= $request->time )
			{
				// create message that can not be setup for required players
				if ( $request->players * 2 < Player::getPlayersAvailable ()->count () && $request->time_confirm >= $request->time )
				{
					//flash ( 'Error. There are more then ' . $request->players * 2 . ' players available and last confirmation time must be bigger the game start time.', 'danger' );
					return \Redirect::to ( 'admin/games' );
				} elseif ( $request->time_confirm >= $request->time )
				{
					//flash ( 'Last confirmation time must be bigger then game start time.', 'danger' );
					return \Redirect::to ( 'admin/games' );
				} else
				{
					//flash ( 'There are more then ' . $request->players * 2 . ' players available', 'danger' );
					return \Redirect::to ( 'admin/games' );
				}
			}
			try
			{
				\DB::table ( 'week_days' )->update ( [ 'day_match' => false, 'start_time' => '00:00:00', 'last_confirm_time' => '00:00:00' ] );
				\DB::table ( 'week_days' )->where ( 'day', $request->days )
                    ->update ( [
                        'day_match' => true,
                        'start_time' => $request->time,
                        'last_confirm_time' => $request->time_confirm
                    ] );

                $thisGame = Game::orderBy('id')->get()->last();
                $thisGame->players_a_side = $request->get('players', 7);

                $gameManager = new GameManager();
                $isCurrentGameCancelled = $gameManager->isCurrentGameCancelled();
                if($isCurrentGameCancelled){
                    $thisGame->starts_at = $gameManager->getThisDateGame()->addWeek();
                }else{
                    $thisGame->starts_at = $gameManager->getThisDateGame();
                }

                $thisGame->save();

                \Log::info(Auth::user()->username . " on ".Carbon::now()." updated the game: ", [$thisGame]);
				//flash ( 'New game setup has been updated successfully.', 'success' );
			} catch ( \Exception $e )
			{
				//flash ( 'Changes has not been applied', 'danger' );
			}

			return redirect ( 'admin/games' );
		}

		public function complete_game ( Request $request )
		{
			$players = Player::all ();
			// get current game that has not teams generated
			$last_game = Game::getCurrentWeekGame ();
			try {
				foreach ( $players as $player )
				{
					if ( $player->status == 'available' )
					{
						if ( $request->result == 'red' && $player->current_team == 'red' )
						{
							$player->games_won += 1;
							$player->points += 3;
						} elseif ( $request->result == 'green' && $player->current_team == 'green' )
						{
							$player->games_won += 1;
							$player->points += 3;
						} elseif ( $request->result == 'draw' )
						{
							$player->games_draw += 1;
							$player->points += 1;
						} else
						{
							$player->games_lost += 1;
						}

                        $this->updateGamePlayer($player, $last_game);

                        $player->total_games += 1;
                        $player->status = 'waiting';
                        $player->current_team = null;
					}
					if ( $player->status == 'unavailable' )
					{
						if ( $player->unavailable_for == 2 )
						{
							$player->unavailable_for = ( substr ( $player->unavailable_for, 0, 1 ) - 1 ) . ' week';
						} elseif ( $player->unavailable_for > 2 )
						{
							$player->unavailable_for = ( substr ( $player->unavailable_for, 0, 1 ) - 1 ) . ' weeks';
						} else
						{
							$player->unavailable_for = '0';
							$player->status = 'waiting';
						}
					}
					$player->save ();
				}
				$last_game->result = $request->result;
				$last_game->current = 0;
				$last_game->status = 'completed';
				$last_game->save ();

                \Log::info(Auth::user()->username . " on ".Carbon::now()." completed the game");

//                Game::createGame();
                return \Response::json(['status' => "success", 'message' => "Current game has been completed"]);
			} catch ( \Exception $e )
			{
                \Log::error($e);
                return \Response::json(['status' => "error", 'message' => "Changes has not been applied to the current game"]);
			}
		}

		// reset players and cancel the game for the current week
		public function cancel_game (){

            $gameManager = new GameManager();

			if(!$gameManager->isCurrentGameCancelled()){
				$set_generated = Game::orderBy( 'id', 'desc' )->first();
				$set_generated->generated_by = -1;
				$set_generated->status = 'canceled';
				$set_generated->current = 0;
				$set_generated->save();

                \Log::info(Auth::user()->username . " on ".Carbon::now()." cancelled the game");
                Game::createGame();
				Player::resetPlayers();
			}

		}

		public function cancel_teams ()
		{

			if ( Game::getCurrentWeekGame ()->generated_by )
			{
				try
				{
                    \DB::statement(
                        "UPDATE players SET current_team = null"
                    );

					$last_game = Game::getCurrentWeekGame ();
					$last_game->generated_by = 0;
					$last_game->save ();
                    VisitorsBuilder::createVisitor("team reset");
                    \Log::info(Auth::user()->username . " on ".Carbon::now()." had reset the teams");

                    return response()->json(['message' => 'Success'], 200);
				} catch ( Exception $e ){
                    \Log::error("Error resetting the teams");
				}
			} else {
                \Log::error("Teams not generated yet");
			}
            return response()->json([], 422);
		}

		public function getPlayersList() {
			$users = User::select(
			    "users.id", "users.username", "users.name", "players.unavailable_for", "players.status", "players.last_confirm", "players.active", "players.player_position", "players.skill_level"
            )->join('players', 'players.user_id', '=', 'users.id')
                ->orderBy('players.last_confirm', 'desc')
				->get();

			$numbering = 1;
			foreach ($users as $key => $user)
			{
                    $user->username = $user->username . ' (' . $user->name . ')';
                    $user->numbering = '<strong>' . $numbering . '.</strong>';
                    $no_not_available = ($user->unavailable_for > 0) ? ' (' . $user->unavailable_for . ')' : '';
                    $user->status = $user->status . '' . $no_not_available;
//				$user->btns = '<btn class="btn btn-primary btn-xs margin-r-5" title="Edit" onclick="window.location=\'users/update/'.$user->id.'\'">';
                    $user->btn_update = '<btn class="btn btn-primary btn-xs btn-raised margin-r-5" style="width: 80px" title="Edit" id="btnEditPlayer" data-id="' . $user->id . '">';
                    $user->btn_update .= '<i class="fa fa-pencil-square-o"></i> Edit</btn>';
                    if ($user->active) {
                        $user->btn_status .= '<btn class="btn btn-danger btn-xs btn-raised" style="width: 80px" title = "Disable" id="btnStatusPlayer" data-status-type="disable" data-id="' . $user->id . '">';
                        $user->btn_status .= '<i class="fa fa-trash-o" ></i> Disable</btn >';
                    } else {
                        $user->btn_status .= '<btn class="btn btn-success btn-xs btn-raised" style="width: 80px" title = "Active" id="btnStatusPlayer" data-status-type="active" data-id="' . $user->id . '">';
                        $user->btn_status .= '<i class="fa fa-plus-square" ></i> Active</btn >';
                    }
                    $numbering++;
			}
			echo json_encode( $users, JSON_NUMERIC_CHECK );
		}

		public function getPlayers() {
            VisitorsBuilder::createVisitor();
			return view('admin.players', [
				'player' => Player::getPlayerFullDetails()
			]);
		}

		public function getPlayerDetails($id) {

            VisitorsBuilder::createVisitor("player_update");
			$user = User::find($id);
			$player = $user->player()->first();
            $authUser = Auth::user();
			$playerDetails = view('admin.includes.player_update',
                [
                    'player' => $player,
                    'user' => $user,
                    'authUser' => $authUser
                ]
            )->render();

			return \Response::json(['playerDetails' => $playerDetails]);
		}
		
		public function updatePlayerDetails(Request $request, $id) {

			$user = User::find($id);

            if($user === null){
                \Log::error("User id ({$id}) not found");
                return \Response::json(['status' => "fail"], 404);
            }

            $thisPlayer = $user->player;
            if($thisPlayer === null){
                \Log::error("Player not found fro user id ({$id})");
                return \Response::json(['status' => "fail"], 404);
            }

			$validator = Validator::make($request->all(), [
				'name' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users,email,' . $id,
				'username' => 'required|max:255',
			]);
			if ($validator->fails()) {
				return array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
				);
			}
			
			$user->update(
				[
					'email' => $request->get( 'email' ),
					'name' => ucwords( $request->get( 'name' ) ),
					'username' => ucwords( $request->get( 'username' ) ),
					'role' => $request->get( 'role' )
				]
			);

			if ( $request->get( 'status' ) === 'unavailable' ){
				$unavailable_for = "1 week";
			} else{
				$unavailable_for = 0;
			}

            $authUser = Auth::user()->username;
            $logInfo = $authUser . ' updated ' . $user->username;
            $logInfoArr = [];

            $playerPosition = $request->get( 'position', 'defender' );
            if($thisPlayer->player_position != $playerPosition){
                $logInfoArr[] = "position:  {$playerPosition}, before: {$thisPlayer->player_position} - now {$playerPosition}";
            }

            $skill_level = $request->get( 'skill', 3 );
            if($thisPlayer->skill_level != $skill_level){
                $logInfoArr[] = "skill_level:  {$skill_level}, before: {$thisPlayer->skill_level} - now {$skill_level}";
            }

            $status = $request->get( 'status', 'waiting' );
            if($thisPlayer->status != $status){
                $logInfoArr[] = "status:  {$status}, before: {$thisPlayer->status} - now {$status}" ;
            }

            $ranking_priority = $request->get('ranking_priority', 1);
            if($thisPlayer->ranking_priority != $ranking_priority){
                $logInfoArr[] = "ranking_priority:  {$ranking_priority}, before: {$thisPlayer->ranking_priority} - now {$ranking_priority}" ;
            }

            if($thisPlayer->unavailable_for != $unavailable_for){
                $logInfoArr[] = "unavailable_for:  {$unavailable_for}, before: {$thisPlayer->unavailable_for} - now {$unavailable_for}";
            }

            if(count($logInfoArr)){
                \Log::info($logInfo ."<br>" . implode(",<br>", $logInfoArr));
            }

            $thisPlayer->player_position = $playerPosition;
            $thisPlayer->skill_level = $skill_level;
            $thisPlayer->status = $status;
            $thisPlayer->ranking_priority = $ranking_priority;
            $thisPlayer->unavailable_for = $unavailable_for;
            $thisPlayer->save();

			$is_temp = $request->get("is_temp", "N") === "Y";
			if($is_temp){
                Mail::send('emails.activate_account', ['user' => $user->toArray()], function ($message) use ($user) {
                    $message->from('champs@cyptech.uk', 'Account activation process');
                    $message->to($user->email)->subject('Cyptech, account activation');
                });

                \Log::info("Email sent by " . $authUser ." to user email address: " . $user->email . " to activate temp account ");
            }

            VisitorsBuilder::createVisitor("update, username: " . $user->username);
			if($request->get( 'status' ) === "available"){
                MailController::send(Player::isPlayersNeeded());
            }
			return \Response::json(['status' => "OK"]);
		}
		
		public function getCreatePlayer() {
            VisitorsBuilder::createVisitor("player_create");
		    $authUser = Auth::user();
			$newPlayerForm = view('admin.includes.player_create', ["authUser" => $authUser])->render();
			return \Response::json(['newPlayerForm' => $newPlayerForm]);
		}
		
		public function createNewPlayer(Request $request) {

			$validator = Validator::make($request->all(), [
				'name' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users',
				'username' => 'required|max:255|unique:users',
				'password' => 'required|min:3|confirmed',
				'password_confirmation' => 'min:3',
			]);
			if ($validator->fails()) {
				return array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
				);
			}
			$is_temp = $request->get("is_temp", "N");


			$user = User::create([
				'email' => $request->get('email'),
				'name' => ucwords($request->get('name')),
				'username' => ucfirst($request->get('username')),
				'password' => bcrypt($request->get('password')),
				'role' => $request->get('role'),
                'remember_token' => Str::random(60),
                'is_temp' => $is_temp
			]);

			Player::create([
				'user_id' => $user->id,
				'skill_level' => $request->get('skill'),
				'status' => $request->get('status'),
				'active' => 1,
				'ranking_priority' => $request->get('ranking_priority', 1),
			]);
            $authUser = Auth::user()->username;
            \Log::info("New account created by " . $authUser . ", new user email address: " . $user->email . " ");

			if($is_temp === "Y"){
                Mail::send('emails.activate_account', ['user' => $user->toArray()], function ($message) use ($user) {
                    $message->from('champs@cyptech.uk', 'Account activation process');
                    $message->to($user->email)->subject('Cyptech, account activation');
                });

                \Log::info("Email sent by " . $authUser ." to user email address: " . $user->email . " to activate temp account ");
            }
            VisitorsBuilder::createVisitor("new, username: " . $user->username);
            
            if($request->get( 'status' ) === "available"){
                MailController::send(Player::isPlayersNeeded());
            }
            
			return \Response::json(['status' => "OK"]);
		}
		
		public function setDisablePlayer($id) {
			$player = Player::find($id);
			if($player === null){
                \Response::json([], 404);
            }
			$player->active = false;
			$player->status = 'suspended';
			$player->save();
			return \Response::json(['status' => 'OK']);
		}
		
		public function setActivePlayer($id) {
			$player = Player::find($id);
            if($player === null){
                \Response::json([], 404);
            }
			$player->active = true;
			$player->status = 'waiting';
			$player->save();
			return \Response::json(['status' => 'OK']);
		}

        /**
         * @param $player
         * @param $last_game
         */
        private function updateGamePlayer($player, $last_game): void
        {
            $playerId = $player->id ?? 0;
            $last_game_id = $last_game->id ?? 0;
            if ($playerId > 0 && $last_game_id > 0) {
                \DB::statement(
                    "INSERT INTO game_player (game_id, player_id, team) " .
                    "VALUES ({$last_game_id}, {$playerId}, '{$player->current_team}')"
                );
            }
        }
    }
