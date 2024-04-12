<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralSettings extends Controller
{
    public function check_version(Request $request)
    {
       // if(get_setting('force_update'));
        return response()->json([
            'status' => true,
            'version_code' => "3.0",
            'store_link' => "https://play.google.com/store/apps/details?id=com.crypto.miner.masth",
            'custom_link' => null
        ]);
    }
}
