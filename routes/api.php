<?php

use Illuminate\Http\Request;
Route::get('/', 'VMailController@servers');
Route::get('/verify/{email}', 'VMailController@verify')->where('email', '(.*)')->middleware('checkApiKey');
