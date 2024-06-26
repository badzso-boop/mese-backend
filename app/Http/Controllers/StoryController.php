<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index()
    {
        return response()->json(Story::all());
    }

    public function stories()
    {
        $stories = Story::all();

        return view('book', compact('stories'));
    }
}
