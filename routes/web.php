<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostController;


Route::group(['prefix' => '/auth'], function () {

        Route::get('/token',[AuthenticationController::class, 'generateCsfr']);
        Route::post('/login',[AuthenticationController::class, 'login']);
        Route::post('/register',[AuthenticationController::class, 'register']);
        Route::any('/logout/{userId}',[AuthenticationController::class, 'logout'])->middleware('isLoggedIn')->middleware('auth:sanctum');
    
});


Route::group(['prefix' => 'app'], function () {

    Route::get('/posts',[PostController::class, 'getAllPosts'])->middleware('isLoggedIn')->middleware('auth:sanctum');
    Route::post('/posts',[PostController::class, 'createPost'])->middleware('isLoggedIn')->middleware('auth:sanctum');
    Route::post('/posts/react',[PostController::class, 'react'])->middleware('isLoggedIn')->middleware('auth:sanctum');
    Route::post('/posts/view',[PostController::class, 'view'])->middleware('isLoggedIn')->middleware('auth:sanctum');
    Route::post('/posts/comment',[PostController::class, 'comment'])->middleware('isLoggedIn')->middleware('auth:sanctum');





});


Route::any('/', function () {
    return response()->json(['message' => 'Oops, Silence is golden'], 200);
});
