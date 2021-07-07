<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        // $posts = Post::all();
        $posts = Post::with("category")->with("tags")->get();

        return response()->json([
            "success" => true,
            "results" => $posts
        ]);
    }
}
