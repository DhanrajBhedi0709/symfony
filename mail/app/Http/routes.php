<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    $data = [
        'title' => 'Hi Student, I hope u liked Course',
        'content' => 'This laravel course was created with a lot of love and dedication for you'
    ];

    Mail::send('emails.test', $data, function($message){
        $message->to('dhanrajofficials@gmail.com','dhanraj')->subject('Hello Mate');
    });
});

