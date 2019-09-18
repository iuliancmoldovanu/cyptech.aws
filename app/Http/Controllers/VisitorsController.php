<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use App\Player;
use App\Visitor;
use App\WeekDay;
use Illuminate\Http\Response;

class VisitorsController extends Controller {

    public static function index() {

        return view('superadmin.visitors', [
            'player' => Player::getPlayerFullDetails()
        ]);
    }

    public static function table(Request $request) {
        $visitors = Visitor::orderBy($request->get("sort", 'accessed_date'), $request->order);

        if(strlen(trim($request->search)) > 0){
            $visitors ->whereRaw( "CONCAT(username, page, ip, accessed_date) LIKE ?", [ '%' . $request->search . '%' ] );
        }


        return response()->json(
            [
                "total" => $visitors->count(),
                "rows" => $visitors->skip($request->offset)->take($request->limit)->get()
            ]
        );
    }
}
