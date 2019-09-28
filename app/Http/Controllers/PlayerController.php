<?php
namespace App\Http\Controllers;


use App\Helpers\VisitorsBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Player;
use App\User;
use DB;
use Log;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    private $player = null;

    public function getPlayersRanking() {
        VisitorsBuilder::createVisitor();
        return view('users.ranking', [
            'player' => Player::getPlayerFullDetails()
        ]);
    }

    public function getPlayersList() {
        $users = User::with('player')
            ->join('players', 'players.user_id', '=', 'users.id')
            ->where('players.active', true)
            ->orderBy('players.total_games', 'desc')
            ->get();

        foreach ($users as $key => $user){
            $user->username = $user->username . ' (' . $user->name . ')';
        }

        echo json_encode( $users, JSON_NUMERIC_CHECK );
    }

    public function resultGames(){
        return view('player.stats',[
            'player' => Player::getPlayerFullDetails (),
            'isAdmin' => Auth::user()->levelAccess() > 1,
        ]);
    }
    public function tableResultPlayer(Player $player, Request $r){

        $sortBy = $r->get('sort', 'starts_at');
        $order = $r->get('order', 'desc');
        $limit = $r->get('limit', 10);
        $offset = $r->get('offset', 0);
        $year = $r->get('year', 0);
        $year = (int) $year === 0 ? "" : " AND YEAR(g.starts_at) = {$year} ";

        $games = DB::select(
            "SELECT gp.player_id, gp.game_id, ".
            "CASE g.result WHEN 'draw' THEN 'draw'  WHEN gp.team THEN 'win' ELSE 'lost' END AS stats ".
            "FROM game_player AS gp ".
            "JOIN games AS g ON gp.game_id = g.id " .
            "WHERE gp.player_id = {$player->id} {$year} "
        );

        $ls = [
            "win" => '<span class="label label-success"> win</span>',
            "lost" => '<span class="label label-danger">lost</span>',
            "draw" => '<span class="label label-info">draw</span>',
        ];
//        dd($offset, (int) $limit);
        $p_games = DB::select(
            "SELECT gp.player_id, gp.game_id, gp.team, g.team_green, g.team_red, g.result, g.starts_at, ".
            "CASE g.result WHEN 'draw' THEN '{$ls['draw']}' WHEN gp.team THEN '{$ls['win']}' ELSE '{$ls['lost']}' END AS status ".
            "FROM game_player AS gp ".
            "JOIN games AS g ON gp.game_id = g.id " .
            "WHERE gp.player_id = {$player->id} {$year} " .
            "ORDER BY {$sortBy} {$order} LIMIT {$offset}, {$limit} "
        );

        $stats = ["win" => 0, "lost" => 0, "draw" => 0];
        foreach ($games as $g){
            switch ($g->stats){
                case "win":
                    $stats["win"]++;
                    break;
                case "lost":
                    $stats["lost"]++;
                    break;
                default:
                    $stats["draw"]++;
            }
        }

        if(Auth::user()->id !== 2){
            \Log::info(Auth::user()->username . " checked player " . $player->user->username . " period: " . $r->get('year', 'All times'));
        }
        $totalGames = count($games);
        if(count($p_games)){
            $lastDate = $p_games[0]->starts_at;
        }else{
            $lastDate = $player->user->created_at;
        }

        // suspend a player when not played for more than 3 mouths and apply only to role players
        $allowUpdate = Carbon::now()->subQuarter()->gt(Carbon::parse($lastDate)) && $player->user->levelAccess() < 2 && (int) $r->get('year', 0) === 0;

        if($r->input("load", "true") === "true"){
            $years = DB::select(
                "SELECT DISTINCT(YEAR(starts_at)) AS year ".
                "FROM games AS g "
            );

            $players = DB::select(
                "SELECT p.id AS p_id, p.user_id, u.id AS u_id, u.username ".
                "FROM players AS p ".
                "JOIN users AS u ON p.user_id = u.id ".
                "WHERE p.status <> 'suspended' "
            );

            return \Response::json( [
                "total" => $totalGames,
                "rows"  => $p_games ,
                'player' => $player->user->username,
                'allowUpdate' => $allowUpdate,
                'players' => $players,
                'years' => $years,
                'stats' => $stats,
            ] );
        }

        return \Response::json( [
            "total" => $totalGames,
            "rows"  => $p_games ,
            'player' => $player->user->username,
            'allowUpdate' => $allowUpdate,
            'stats' => $stats,
        ] );
    }

    public function suspendPlayer(Request $request) {

        if($this->validateRequest($request) > 200){
            return \Response::json( [], 404 );
        }

        $this->player->status = "suspended";
        $this->player->active = false;
        $this->player->save();

        \Log::info(Auth::user()->username . " suspended player " . $this->player->user->username);

        return \Response::json( [], 200 );
    }

    public function deletePlayer(Request $request) {

        if($this->validateRequest($request) > 200){
            return \Response::json( [], 404 );
        }
        $authUser = Auth::user()->username;

        try{
            // delete related
            $this->player->user()->delete();
            $this->player->delete();
        }catch (\Exception $e){
            \Log::error($authUser . " cannot deleted player ", [$e]);
        }

        \Log::info($authUser . " deleted player " . $this->player->user->username);

        return \Response::json( [], 200 );
    }

    /**
     * @param Request $request
     * @return int
     */
    private function validateRequest(Request $request) : int
    {
        $id = $request->input("player_id", false);

        if (!$id) {
            return 404;
        }

        $this->player = Player::with('user')->find($id);
        if ($this->player === null) {
            return 404;
        }

        if ($this->player->user->levelAccess() > 1) {
            return 403;
        }
        return 200;
    }
}
