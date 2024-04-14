<?php

use Illuminate\Support\Facades\File;
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
use Illuminate\Support\Collection;

Route::get('/', function () {
    $file = File::get(base_path('public\olduser.json'));
     $json = json_decode(json: $file, associative: true);;
    return  collect($json)->where('email','codeAbinash@gmail.com')->value('balance');
});
