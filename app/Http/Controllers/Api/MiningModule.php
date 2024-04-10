<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MiningSession;

class MiningModule extends Controller
{

    public function checkMiningStatus(Request $request)
    {

        $mining = MiningSession::where([
            'user_id' => $request->user()->id,
            'status' => 'running'
        ])->get();
        if ($mining->count() > 0) {
            return response()->json([
                'status' => false,
               
                'minning_data' => $mining->first(),
                'message' => 'An Active Minning Session is already Running'
            ]);
        }
        if (!get_setting('mining_function')) {
            return response()->json([
                'status' => false,
               
                'message' => "mining Currently Turned Off"
            ]);
        }
        return response()->json([
            'status' => true,

            'message' => 'No mining Session is currently Running'
        ]);
    }
    public function startMining(Request $request)
    {
        if (!get_setting('mining_function')) {
            return response()->json([
                'status' => false,
                'mining_function' => false,
            ]);
        }
        $mining = MiningSession::where([
            'user_id' => $request->user()->id,
            'status' => 'running'
        ])->get();
        if ($mining->count() > 0) {
            return response()->json([
                'status' => false,
                
                'message' => 'An Active Minning Session is already Running'
            ]);
        }
        $new = new MiningSession;
        $new->session_id = time();
        $new->user_id = $request->user()->id;
        $new->start_time = Carbon::now();
        $new->end_time = Carbon::now()->addHours(3);
        $new->coin = 3;
        $new->save();
          return response()->json([
            'status' => true,
            'message' => 'Mining Session Submit SuccessFully'
        ]);
    }
}
