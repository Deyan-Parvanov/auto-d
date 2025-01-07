<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        // dd(Auth::check());
        return response()->json('Hello from Laravel!');
        
    }
}
