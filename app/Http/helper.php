<?php

use App\Models\Settings;
use App\Models\CoinsTransaction;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

if (!function_exists('get_setting')) {
    function get_setting($key, $group = 'general')
    {
        $result = Settings::where('name', $key)->where('group', $group)->first('payload');
        if ($result != null) {
            return str_replace('"', '', $result->payload);;
        } else {
            return 0;
        }
    }
}
if (!function_exists('coin_action')) {
    function coin_action(int $user_id, float $coins, $type = "debit", $description = null, $meta = [])
    {
        $user = User::findOrFail($user_id);
        if ($user)
            $transaction = new CoinsTransaction;
        $transaction->user_id = $user_id;
        $transaction->coin = $coins;
        $transaction->transaction_type = $type;
        $transaction->description = $description;
        $transaction->transaction_id = 'MST' . time();
        $transaction->status = 'success';
        $transaction->meta = json_encode($meta);
        if ($transaction->save()) {
            if ($type == "credit") {
                $user->increment('coin', $coins);
                return true;
            } else {
                $user->decrement('coin', $coins);
                return true;
            }
        }
        return false;
    }
    function sendpush($user_id,$text)
    {
        $params = [];
        $params['android_channel_id'] = '7fbda4a1-81c5-4eb6-9936-a80543c5c06f';
        OneSignal::addParams($params)->sendNotificationToExternalUser(
            $text,
            $user_id,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
    }
}
