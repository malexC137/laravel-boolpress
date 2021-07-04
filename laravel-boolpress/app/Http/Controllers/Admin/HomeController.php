<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $statistics = [
            "posts" => Post::count(),
        ];

        return view("admin.home", compact("statistics"));
    }
}
