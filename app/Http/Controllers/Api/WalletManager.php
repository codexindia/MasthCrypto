<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CoinsTransaction;

class WalletManager extends Controller
{
    public function getTransaction(Request $request)
    {
        $record = CoinsTransaction::
        select(['coin','transaction_type','description','transaction_id','status','created_at'])
        ->where('user_id', $request->user()->id)
        ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $record,
            'message' => 'Retreive Success'
        ]);
    }
    public function getNameByUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4|exists:users,username'
        ]);
        $user = User::select(['name','username','profile_pic'])->where('username',$request->username)->first();
        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'Retreive Success'
        ]);
    }
}
