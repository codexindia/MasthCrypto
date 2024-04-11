<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReferData;
use App\Models\MiningSession;
class HomeController extends Controller
{
    public function Statics(Request $request)
    {
        $user_id = $request->user()->id;
        $data = array();
        $data['active_miners'] = ReferData::where('user_id', $user_id);
       
        return response()->json([
            'status' => true,
            'active_miners' => $data['active_miners']->count(),
            'total_miners' => $data['active_miners']->count(),
           
            'total_live_mining' => MiningSession::sum('coin'),
            'total_remote_mining' => $data['active_miners']->first() == null ?0:$data['active_miners']->first()->GetMiningCoin,
        ]);
    }
}