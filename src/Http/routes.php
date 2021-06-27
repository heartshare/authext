<?php

use Iehong\AuthExt\Http\Controllers\AuthextController;
use Iehong\AuthExt\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('auth/login', AuthextController::class . '@getLogin')->name('auth.getlogin');
Route::post('auth/login', AuthextController::class . '@postLogin')->name('auth.postlogin');
Route::post('auth/sms', AuthextController::class . '@postSms')->name('auth.sms');
Route::get('auth/setting', AuthextController::class . '@getSetting')->name('auth.getSetting');
Route::match(['put', 'patch'], 'auth/setting', AuthextController::class . '@putSetting')->name('users.putSetting');

Route::get('auth/users', UserController::class . '@index')->name('users.index');
Route::get('auth/users/create', UserController::class . '@create')->name('users.create');
Route::get('auth/users/{user}', UserController::class . '@show')->name('users.show');
Route::get('auth/users/{user}/edit', UserController::class . '@edit')->name('users.edit');
Route::match(['put', 'patch'], 'auth/users/{user}', UserController::class . '@update')->name('users.update');
