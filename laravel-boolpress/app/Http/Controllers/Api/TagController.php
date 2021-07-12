<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller {
    public function index() {
        return response()->json([
            "success" => true,
            "results" => Tag::all()
        ]);
    }
}