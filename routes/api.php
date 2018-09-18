<?php

use Illuminate\Http\Request;
Route::get('/', 'VMailController@servers');
Route::get('/{api_key}/verify/{email}', 'VMailController@verify')->where('email', '(.*)');
