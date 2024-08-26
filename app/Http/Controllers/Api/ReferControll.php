<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReferData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReferControll extends Controller
{
    public function claim(Request $request)
    {
        $request->validate([
            'refer_code' => 'required|exists:users,refer_code'
        ]);
        $user = User::findOrFail($request->user()->id);
        if ($user->referred_by != null || $user->referred_by == "skiped") {
            return response()->json([
                'status' => false,
                'message' => 'Referred Status Can not Update'
            ]);
        }
        $sourceuser = User::where('refer_code', $request->refer_code)->first(['id', 'country_code', 'phone_number']);
        ReferData::create([
            'user_id' => $sourceuser->id,
            'referred_to' => $user->id,
            'coins_earn' => get_setting('referral_coin'),
        ]);
        $user->update([
            'referred_by' => $request->refer_code,
        ]);
        sendpush($sourceuser, '@' . $user->username . ' Just Joining Through Your Refer Code');
        return response()->json([
            'status' => true,
            'message' => 'Referred Status Updated'
        ]);
    }
    public function skip(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $user->update([
            'referred_by' => 'skiped',
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Referred Status Updated'
        ]);
    }
    public function get_referred_stats(Request $request)
    {
        $user_id =  $request->user()->id;
        $coins_earn = ReferData::where('user_id', $user_id)->sum('coins_earn');
        $totalUnclamied = ReferData::where('user_id', $user_id)->where('claimed', 0)->count();

        $RoundUpTHeCount = floor($totalUnclamied/5)*5;
        return response()->json([
            'status' => true,
            'referred_bonus' => get_setting('referral_coin'),
            'coins_earned' => $coins_earn,
            'totalReferred' =>  $totalUnclamied,
            'totalUnclaimed' => $RoundUpTHeCount*100,
            //'activeUsers' => $activeUsers,
            //'inactiveUsers' => $InactiveMembers,
        ]);
    }
    public function claimReferTask(Request $request)
    {
        $user_id =  $request->user()->id;
       // $coins_earn = ReferData::where('user_id', $user_id)->sum('coins_earn');
        $totalUnclamied = ReferData::where('user_id', $user_id)->where('claimed', 0)->count(); //26
        if ($totalUnclamied == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No Unclaimed Bonus'
            ]);
        }
        $RoundUpTHeCount = floor($totalUnclamied/5)*5;
        ReferData::where('user_id', $user_id)->where('claimed', 0)->limit($RoundUpTHeCount)->
        increment('coins_earn',100,[
            'claimed' => 1,
            //'coins_earn' => 
            ]);
    coin_action($user_id,$RoundUpTHeCount * 100,null,"Refer Bonus Added") ;
        return response()->json([
            'status' => true,
            'message' => 'Bonus Claimed'
        ]);
    }
    public function get_referred_members(Request $request)
    {
        $user_id =  $request->user()->id;
        $coins_earn = ReferData::where('user_id', $user_id)->sum('coins_earn');
        if ($request->status == "active") {


            $activeUsers = User::join('mining_sessions', 'users.id', '=', 'mining_sessions.user_id')
                ->select(
                    'users.profile_pic',
                    'users.name',
                    'users.phone_number',
                    'users.country_code',
                    'users.username',
                    DB::raw('MAX(mining_sessions.end_time) as last_active_time')
                )
                ->where('users.referred_by', $request->user()->refer_code) // Replace with your referral code
                ->where('mining_sessions.end_time', '>=', Carbon::now()->subHours(24))
                ->groupBy('users.id', 'users.profile_pic', 'users.name', 'users.phone_number','users.username', 'users.country_code')
                ->paginate(10);
            //   $twentyFourHoursAgo = Carbon::now()->subHours(24);

            return response()->json([
                'status' => true,
                // 'referred_bonus' => get_setting('referral_coin'),
                // 'coins_earned' => $coins_earn,
                'activeUsers' => $activeUsers,
                //'inactiveUsers' => $InactiveMembers,
            ]);
        } else {
            // Query to get users who have not mined in the last 24 hours, were referred by you, and include their last mining time
            $InactiveMembers = User::leftJoin('mining_sessions', 'users.id', '=', 'mining_sessions.user_id')
                ->select(
                    'users.profile_pic',
                    'users.name',
                    'users.phone_number',
                    'users.username',
                    'users.country_code',
                    DB::raw('MAX(mining_sessions.end_time) as last_active_time')
                )
                ->where('users.referred_by', $request->user()->refer_code) // Replace with your referral code
                ->groupBy('users.id', 'users.profile_pic', 'users.name', 'users.phone_number', 'users.country_code','users.username')
                ->havingRaw('MAX(mining_sessions.end_time) IS NULL OR MAX(mining_sessions.end_time) < ?', [Carbon::now()->subHours(24)])
                ->paginate(10);
            // return $InactiveMembers;
            //   $coins_earn = ReferData::where('user_id', $user_id)->sum('coins_earn');
            return response()->json([
                'status' => true,
                //  'referred_bonus' => get_setting('referral_coin'),
                //  'coins_earned' => $coins_earn,
                // 'activeUsers' => $activeUsers,
                'inactiveUsers' => $InactiveMembers,
            ]);
        }
    }
}
