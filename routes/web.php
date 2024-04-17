<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;

Route::get('/stories', [StoryController::class, 'index']);
Route::get('/', [StoryController::class, 'stories']);