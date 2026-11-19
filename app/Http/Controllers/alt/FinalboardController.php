<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class FinalboardController extends Controller
{
    //

    public function index(Request $request)
    {
        session()->flush();
        return view('finalboard.index'); 
    }

}

