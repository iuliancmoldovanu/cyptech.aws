<?php
/**
 * Created by PhpStorm.
 * User: Iulian
 * Date: 19/10/2017
 * Time: 20:39
 */

namespace App\Library\Classes;

use App\Game;
use App\Player;
class Teams{
	
	public static function getGames(){
		$games = Game::get();
		
		$gamesCollection = collect();
		foreach( $games as $game ){
			$gamesCollection->push(self::getGameTeams($game->id));
		}
		return $gamesCollection;
	}
	
	public static function getGameTeams($game_id){
		$game = Game::find($game_id);
		$teamGreen = collect(); $teamRed = collect();
		foreach( $game->players as $player ){
			if($player->pivot->team === "green"){
				$teamGreen->push($player);
			}else{
				$teamRed->push($player);
			}
		}
		return collect(["red" => $teamRed, "green" => $teamGreen]);
	}
	
	public static function getPlayerGames($player_id){
		$player = Player::find($player_id);
		dd($player->games);
		$teamGreen = collect(); $teamRed = collect();
		foreach( $game->players as $player ){
			if($player->pivot->team === "green"){
				$teamGreen->push($player);
			}else{
				$teamRed->push($player);
			}
		}
		return collect(["red" => $teamRed, "green" => $teamGreen]);
	}
}