<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileManager extends Controller
{
    public function GetUser(Request $request)
    {
        $data = $request->user();
        return response()->json([
            'status' => true,
            'data' => $data,
            'refer_claimed' => false,
            'message' => 'done'
        ]);
    }
}
