<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stories', [StoryController::class, 'index']);
Route::get('/sztorik', [StoryController::class, 'stories']);