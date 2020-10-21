<?php
use Illuminate\Support\Facades\Route;

Route::post('register','UserController@register')->name('auth.register');
Route::post('login','UserController@login')->name('auth.login');
Route::get('logout','UserController@logout')->name('auth.logout');
Route::get('user','UserController@user')->middleware('auth:sanctum')->name('auth.user');

Route::get('channels','ChannelController@get')->name('channel.get');

