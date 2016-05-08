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

Route::get('/', function () {
    return "hello World!!!";
});
Route::get('/register/confirm/{token}','AuthController@confirmEmail');

Route::get('/register',[
    'uses'=>'\App\Http\Controllers\Auth\AuthController@getRegistration',
    'as'=>'auth.register',
    'middleware'=>['guest']
]);

Route::post('/register',[
    'uses'=>'\App\Http\Controllers\Auth\AuthController@postRegistration',
    'as'=>'auth.register'
]);
Route::get('/login',[
  'uses'=>'\App\Http\Controllers\Auth\AuthController@getLogin',
  'as'=>'auth.login',
  'middleware'=>['guest']
]);

Route::post('/login',[
  'uses'=>'\App\Http\Controllers\Auth\AuthController@postLogin',
  'as'=>'auth.login',
  'middleware'=>['guest']
]);
Route::get('/logout', [
        'uses' => '\App\Http\Controllers\AuthController@logout',
        'as'   => 'auth.logout'
    ]);
