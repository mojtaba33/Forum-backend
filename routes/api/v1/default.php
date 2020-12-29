<?php
use Illuminate\Support\Facades\Route;

Route::post('register','AuthController@register')->name('auth.register');
Route::post('login','AuthController@login')->name('auth.login');
Route::get('logout','AuthController@logout')->name('auth.logout');
Route::get('check','AuthController@checkLogin')->name('auth.checkLogin');

Route::get('user','UserController@show')->middleware('auth:sanctum')->name('auth.show');

Route::get('channels','ChannelController@index')->name('channel.index');

Route::resource('thread','ThreadController');
Route::patch('thread/{thread}/bestAnswer','ThreadController@setBestAnswer')->name('thread.best.answer');

Route::resource('answer','AnswerController')->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function (){
    Route::post('subscribe/thread/{thread}','SubscriptionController@subscribe')->name('subscribe.thread');
    Route::post('unsubscribe/thread/{thread}','SubscriptionController@unsubscribe')->name('unsubscribe.thread');
});
