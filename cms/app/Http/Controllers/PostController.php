<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function contact()
    {
        return view('contact', compact('id'));
    }

    public function show_post($id)
    {
        return view('post', compact('id'));
    }

}
