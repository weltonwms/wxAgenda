<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('telegram/subscribe','\App\Http\Controllers\TelegramController@subscribe');
Route::get('telegram/teste','\App\Http\Controllers\TelegramController@teste');

Route::post('payments', function (Request $request) {
    return ["message"=>"Endpoint em construção", 'request'=>$request->all()];
});
