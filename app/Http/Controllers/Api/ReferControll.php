<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        $user->update([
            'referred_by' => $request->refer_code,
        ]);
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
}
