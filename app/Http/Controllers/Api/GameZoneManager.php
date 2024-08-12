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
        $gameData = GameZone::where('category', $request->cat)
            ->select(
                DB::raw("CONCAT('$url/',thumbnail) AS thumbnail"),
                'gameName',
                'gameWebLink',
                'rewardCoins',
                'category'
            )
            ->orderBy('gameId', 'desc')->get();
            $carousal = array([
                'name' => 'asdasd',
                'imgSrc' => url('images/car1.png')
            ],
            [
                'name' => 'asdasd',
                'imgSrc' => url('images/car1.png')
            ]);
        return response()->json([
            'status' => true,
            'carousal' => $carousal,
            'data' => $gameData,
            'message' => 'games successfully retrieves'
        ]);
    }
}
