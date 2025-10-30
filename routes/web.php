<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login_admin.login_admin');
});
