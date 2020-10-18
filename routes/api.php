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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::namespace('App\Http\Controllers\api\v1')->group(function (){
    Route::post('register','UserController@register')->name('auth.register');
    Route::post('login','UserController@login')->name('auth.login');
    Route::get('logout','UserController@logout')->name('auth.logout');
    Route::get('user','UserController@user')->middleware('auth:sanctum')->name('auth.user');

    Route::get('channels','ChannelController@get')->name('d.channel.get');
});

Route::prefix('admin')->namespace('App\Http\Controllers\api\v1\Admin')->middleware(['auth:sanctum','checkAdmin'])->group(function (){
    Route::resource('channel','ChannelController');
});

