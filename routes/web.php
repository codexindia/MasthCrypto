<?php

use Illuminate\Support\Facades\Route;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $params = [];
    $params['android_channel_id'] = '7fbda4a1-81c5-4eb6-9936-a80543c5c06f';
   return  OneSignal::addParams($params)->sendNotificationToExternalUser(
    "Some Message",
    '918509435513',
    $url = null,
    $data = null,
    $buttons = null,
    $schedule = null
);


});
