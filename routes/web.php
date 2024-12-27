<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // Your main Vue entry view
})->where('any', '.*');
