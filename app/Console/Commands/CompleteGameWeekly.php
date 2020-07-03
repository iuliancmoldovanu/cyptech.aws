<?php

namespace App\Console\Commands;

use App\Game;
use App\Library\Classes\GameManager;
use App\Player;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompleteGameWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update the game if no action has been taken.';

    /**
     * Create a new command instance.
     * The game will be automatically completed Sunday midnight if still running
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastGame = Game::orderBy( 'id', 'desc' )->first();
        if(Carbon::now() > Carbon::parse($lastGame->starts_at) ){
            // update current game
            Game::updateActive($lastGame);
        }
    }
}
