<?php

namespace App\Http\Controllers;

use App\Player;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller {

    /**
     *  Sending email when the number of places reached
     * @param $places_left
     */
    public static function send($places_left) {
        if($places_left > 0){
            // list of players email
            $users = User::whereHas('player', function($q) use ($places_left){
                    $q->where('notify_places', $places_left)
                        ->where('status', "waiting");
                })
                ->select('id', 'email', 'username')->get();
    
            $messageHTML = 'Don\'t forget to confirm this week. Access http://cyptech.uk to confirm now. <br>You can turn off this notification by accessing your account profile.';
    
            foreach ($users as $user) {
                Mail::raw($messageHTML, function ($message) use ($user, $places_left) {
                    $message->from('champs@cyptech.uk', 'Cyptech Weekly Notification');
                    $message->to($user->email)->subject('Only ' . $places_left . ' places left !');
                });
                \Log::info("Email sent on waiting player email:" . $user->email . " Places left: ". $places_left);
            }
        }
    }
}
