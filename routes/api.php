<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 

 Route::get('/demo', function () {
    return response()->json(['API Demonstration Success'], 200);
});
