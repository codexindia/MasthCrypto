<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameZone;
use Illuminate\Support\Facades\DB;
use App\Models\GamePlayHistory;

use function Laravel\Prompts\select;

class GameZoneManager extends Controller
{
    public function getGames(Request $request)
    {
        $url = url("/storage//");
        $gameData = GameZone::where('category', $request->cat)
            ->select(
                DB::raw("CONCAT('$url/',thumbnail) AS thumbnail"),
                'gameId',
                'gameName',
                'gameWebLink',
                'rewardCoins',
                'category'
            )
            ->orderBy('gameId', 'desc')->get();
        $playTime = GamePlayHistory::where([
            'userId' => $request->user()->id,
            //'claimed' => '0',
        ])->count();
        $carousal = array(
            [
                'name' => 'asdasd',
                'imgSrc' => url('images/car1.png')
            ],
            [
                'name' => 'asdasd',
                'imgSrc' => url('images/car1.png')
            ]
        );
        return response()->json([
            'status' => true,
            'carousal' => $carousal,
            'data' => $gameData,
            'playTime' => $playTime,
            'message' => 'games successfully retrieves'
        ]);
    }
    public function gameActivity(Request $request)
    {
        $request->validate([
            'gameId' => 'required|exists:game_zones,gameId'
        ]);
        $gameId = $request->input('gameId');
        $userId = $request->user()->id;
        //   $timePlayed = 1;

        // Store the time played by the user per minute
        DB::table('game_play_histories')->insert([
            'gameId' => $gameId,
            'userId' => $userId,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Game activity stored successfully'
        ]);
    }
    public function checkClaim(Request $request)
    {
        $userId = $request->user()->id;
        $playedMiniute = GamePlayHistory::where([
            'userId' => $userId,
            'claimed' => '0',
        ])->count();
        // return $playedMiniute;
        if (!$playedMiniute) {
            return response()->json([
                'status' => false,
                'message' => 'No Claim Available'
            ]);
        }
        $gameIds = GamePlayHistory::where([
            'userId' => $userId,
            'claimed' => '0',
        ])->get('gameId');
        //   return   $gameIds;
        $coin = GameZone::whereIn('gameId', $gameIds)->first();
        return response()->json([
            'status' => true,
            'thumbnail' => url('storage/'.$coin->thumbnail),
            'canClaimCoin' => $playedMiniute * $coin->rewardCoins,
            'message' => 'games successfully retrieves'
        ]);
    }
    public function claimReward(Request $request)
    {
        $userId = $request->user()->id;
        $playedMiniute = GamePlayHistory::where([
            'userId' => $userId,
            'claimed' => '0',
        ])->count();
        if (!$playedMiniute) {
            return response()->json([
                'status' => false,
                'message' => 'No Claim Available'
            ]);
        }
        $gameIds = GamePlayHistory::where([
            'userId' => $userId,
            'claimed' => '0',
        ])->get('gameId');
        $coin = GameZone::whereIn('gameId', $gameIds)->first();
        $totalCoin = $playedMiniute * $coin->rewardCoins;
        DB::table('game_play_histories')->where([
            'userId' => $userId,
            'claimed' => '0',
        ])->update(['claimed' => '1']);
       // DB::table('users')->where('id', $userId)->increment('coins', $totalCoin);

        return response()->json([
            'status' => true,
            'message' => 'Claimed Successfully'
        ]);
    }
}
