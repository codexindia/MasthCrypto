<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReferData;
use App\Models\MiningSession;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function Statics(Request $request)
    {
        $user_id = $request->user()->id;
        $data = array();
        $data['active_miners'] = ReferData::where('user_id', $user_id);


        $total_remote_earning = 0;
        $referaluser = User::where('referred_by', $request->user()->refer_code)->get(['id'])->toArray();

        $total_remote_earning = MiningSession::whereIn('user_id', $referaluser)->sum('coin');



        return response()->json([
            'status' => true,
            'valuation' => array(
                'currency' => 'USD',
                'rate' => 0.50,
            ),
            'active_miners' => $data['active_miners']->count(),
            'total_miners' => $data['active_miners']->count(),
            'total_live_mining' => number_format(MiningSession::sum('coin'), 4),
            'total_remote_mining' => number_format($total_remote_earning, 4),

        ]);
    }
}
