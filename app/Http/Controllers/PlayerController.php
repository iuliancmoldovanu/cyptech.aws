<?php
namespace App\Http\Controllers;


use App\Helpers\VisitorsBuilder;
use App\Player;
use App\User;

class PlayerController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

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
}
