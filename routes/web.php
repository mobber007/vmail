<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');
Route::get('/api/{email}', 'VMailController@verify')->where('email', '(.*)')->middleware('auth');
Route::get('/', 'VMailController@show_servers');
