<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerficationCodes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthManagement extends Controller
{
    public function login_OTP(Request $request)
    {
        $request->validate([
            'country_code' => 'required|numeric',
            'phone' => 'required|numeric|exists:users,phone_number|digits:10',
        ], [
            'phone.exists' => 'Phone Number Has Not Registered',
        ]);
        $temp = ['country_code' => $request->country_code];
        if ($this->genarateotp($request->phone, $temp)) {
            return response()->json([
                'status' => true,
                'message' => 'OTP send successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'OTP Send UnsuccessFully Or Limit Exeeded Try Again Later',
            ]);
        }
    }
    private function VerifyOTP($phone, $otp)
    {
        //this for test otp
        if ($otp == "913432") {
            $checkotp = VerficationCodes::where('phone', $phone)
                ->latest()->first();
            VerficationCodes::where('phone', $phone)->delete();
            return $checkotp;
        }
        //end for test otp
        $checkotp = VerficationCodes::where('phone', $phone)
            ->where('otp', $otp)->latest()->first();
        $now = Carbon::now();
        if (!$checkotp) {
            return 0;
        } elseif ($checkotp && $now->isAfter($checkotp->expire_at)) {

            return 0;
        } else {
            $device = 'Auth_Token';
            VerficationCodes::where('phone', $phone)->delete();
            return $checkotp;
        }
    }
    public function login_attempt(Request $request)
    {
        $request->validate([
            'country_code' => 'required|numeric',
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|numeric|exists:users,phone_number|digits:10',
        ]);
        //  return  $this->VerifyOTP($request->phone, $request->otp);
        if ($this->VerifyOTP($request->phone, $request->otp)) {
            $checkphone = User::where('phone_number', $request->phone)->first();
            if ($checkphone) {
                $checkphone->tokens()->delete();
                $token = $checkphone->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified  Successfully (Login)',
                    'token' => $token,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobile Has Not Registered',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your OTP is invalid'
            ]);
        }
    }
    public function SignUp(Request $request)
    {
        $request->validate([

            'country_code' => 'required|numeric',
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|numeric|unique:users,phone_number'
        ]);
        $data = $this->VerifyOTP($request->phone, $request->otp);
        if ($data) {
            $temp = json_decode($data->temp);

            $new_user = User::create([
                'name' => $temp->name,
                'username' => $temp->username,
                'date_of_birth' => $temp->dob,
                'language' => $temp->lang,
                'phone_number' => $request->phone,
                'country_code' => $request->country_code,
            ]);

            $token = $new_user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'OTP Verified  Successfully (Signup)',
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your OTP is invalid'
            ]);
        }
    }
    public function SignUP_OTP(Request $request)
    {
        $request->validate([
            'username' => 'required|max:100',
            'dob' => 'required|max:100',
            'lang' => 'required|max:100',
            'name' => 'required',
            'phone' => 'required|numeric|unique:users,phone_number',
            'country_code' => 'required|numeric'
        ]);
        $temp = [
            'name' => $request->name,
            'country_code' => $request->country_code,
            'username' => $request->username,
            'dob' => $request->dob,
            'lang' => $request->lang,
        ];
        $this->genarateotp($request->phone, $temp);
        return response()->json([
            'status' => true,
            'message' => 'OTP Send Successfully',
        ]);
    }
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);
        $phone = $request->phone;

        if ($this->genarateotp($phone)) {
            return response()->json([
                'status' => true,
                'message' => 'Sms Sent Successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sms Could Not Be Sent',
            ]);
        }
    }
    private function genarateotp($number, $temp = [])
    {
        $otpmodel = VerficationCodes::where('phone', $number);

        if ($otpmodel->count() > 10) {
            return false;
        }
        $checkotp = $otpmodel->latest()->first();
        $now = Carbon::now();

        if ($checkotp && $now->isBefore($checkotp->expire_at)) {

            $otp = $checkotp->otp;
            $checkotp->update([
                'temp' => json_encode($temp),
            ]);
        } else {
            $otp = rand('100000', '999999');
            //$otp = 123456;
            VerficationCodes::create([
                'temp' => json_encode($temp),
                'phone' => $number,
                'otp' => $otp,
                'expire_at' => Carbon::now()->addMinute(10)
            ]);
        };
        $receiverNumber =  $temp['country_code'] . $number;
        $message = "Hello\nMasth Verification OTP is " . $otp;

        try {
            Http::post('https://wpsender.nexgino.com/api/create-message', [
                'appkey' => '175e1921-7d4a-4d1c-93a3-14411d027550',
                'authkey' => 'ZWkn8L2VlIOBLX5pl7omqUdkjR7RDfz6WW8ZSUzjXpy5y974DQ',
                'to' => $receiverNumber,
                'message' => $message,
            ]);


            return true;
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
}
