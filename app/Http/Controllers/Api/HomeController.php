<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function Statics(Request $request)
    {
      return response()->json([
      'status' => true,
      'active_miners' => 20,
      'total_miners' => 20,
      'total_remote_mining' => 30,
      'total_live_mining' => 30,
      ]);
    }
}
