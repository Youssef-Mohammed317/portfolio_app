<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    Mail::raw('protfolio Test', function($message){
        $message->to('email@email.com')->subject('Test');
    });
    return view('welcome');
});
