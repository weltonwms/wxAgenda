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

Route::post('/sso/authenticate', [App\Http\Controllers\SSOController::class, 'authenticate']);
Route::get('/sso/teste', [App\Http\Controllers\SSOController::class, 'teste']);

Route::middleware('static.token.api')->group(function () {
    Route::post("payments","\App\Http\Controllers\PaymentApiController@storeEv");
   
});
Route::post('/paymentswoo', [App\Http\Controllers\PaymentApiController::class, 'storeWoo'])
    ->middleware('woocommerce.auth');

Route::middleware('static.token.student')->group(function () {
    Route::get("students/discount","\App\Http\Controllers\StudentApiController@getDiscount");
    Route::get("students/actives","\App\Http\Controllers\StudentApiController@getActives");
   
});


