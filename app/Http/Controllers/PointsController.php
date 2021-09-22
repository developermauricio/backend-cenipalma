<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Point; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointsController extends Controller
{
    public function getPoints() {
        $listPoints = Point::all();
        return response()->json(['status' => 'ok', 'data' => $listPoints]);
    }

    public function getTotalPointsUser( $email ){
        $user = User::whereEmail($email)->first();
        $points = 0;

        if ( $user ) {
            $points = Point::where('email', $user->email)->sum('points');
        }

        return response()->json(['data' => $points]);
    }

    public function getPointsUser( $email ){
        $user = User::whereEmail($email)->first();
        $points = [];

        if ( $user ) {

            $points = DB::table('points')
                ->where('email', $user->email)
                ->groupBy('name_scene','click_name')
                ->select(DB::raw('name_scene,click_name,SUM(points) as points'))
                ->get();
        }

        return response()->json(['data' => $points]);
    }

    public function getRankingPointsUsers() {
        $rankingPoints = [];

        $rankingPoints = DB::table('points')
        ->groupBy('email', 'fullname')
        ->select(DB::raw('fullname,SUM(points) as points'))
        ->orderBy('points', 'desc') 
        ->limit(10)       
        ->get();

        return response()->json(['data' => $rankingPoints]);
    }
}
