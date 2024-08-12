<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameZone as GameData;
use Illuminate\Support\Facades\DB;

class GameZone extends Controller
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
        $gameData = GameData::where('category', $request->cat)
        ->orderBy('gameId', 'desc')->get();
        return response()->json([
            'status' => true,
            'data' => $gameData,
            'message' => 'games successfully retrieve'
        ]);
    }
}
