<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;
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
    public function UpdateUser(Request $request)
    {
        $update = array();
        if ($request->has('name')) {
            $update['name'] = $request->name;
        }
        if ($request->has('email')) {
            $update['email'] = $request->email;
        }
        if ($request->has('dob')) {
            $update['date_of_birth'] = $request->dob;
        }
        if ($request->hasFile('profile_pic')) {
            $update['profile_pic'] = Storage::put('public/users/profile', $request->file('profile_pic'));
        }
       $user = User::find($request->user()->id);
       $params = [];
       $params['android_channel_id'] = '7fbda4a1-81c5-4eb6-9936-a80543c5c06f';
        OneSignal::addParams($params)->sendNotificationToExternalUser(
            "Your Profile Has Been Updated",
            $user->country_code.$user->phone_number,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        $user->update($update);
        return response()->json([
            'status' => true,
            'message' => 'Details Updated SuccessfUlly'
        ]); 
    }
}
