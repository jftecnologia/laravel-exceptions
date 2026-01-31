<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/error', function () {
    App::setLocale('pt_BR');

    return view('laravel-exceptions::error', [
        'code' => 500,
        'message' => __('laravel-exceptions::exceptions.user.app'),
    ]);
});
