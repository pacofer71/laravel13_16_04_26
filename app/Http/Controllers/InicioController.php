<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function inicio(){
        $posts=Post::with('user')->where('estado', 'Publicado')->latest()->get();
        return view('welcome', compact('posts'));
    }
}
