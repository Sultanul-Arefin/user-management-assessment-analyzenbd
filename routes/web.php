<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return apiResponse(
        data: null,
        message: 'Please, try with your endpoints to get the data'
    );
});
