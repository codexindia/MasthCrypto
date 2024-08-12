<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameZone;
use Illuminate\Support\Facades\DB;

class GameZoneManager extends Controller
{
    public function getGames(Request $request)
    {
        $url = url("/storage//");
        // $gameData = GameData::where('category', $request->cat)
        //     ->select(
        //         DB::raw("CONCAT('$url/',thumbnail) AS thumbnail"),
        //         'gameName',
        //         'gameWebLink',
        //         'rewardCoins',
        //         'category'
        //     )
        //     ->orderBy('gameId', 'desc')->get();
        $gameData = GameZone::where('category', $request->cat)
       ->get();
        return response()->json([
            'status' => true,
            'data' => $gameData,
            'message' => 'games successfully retrieves'
        ]);
    }
}
