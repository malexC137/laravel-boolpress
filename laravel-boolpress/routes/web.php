<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');
Route::get('/posts', 'PostController@index')->name('posts.index');
Route::get('/posts/{slug}', "PostController@show")->name("posts.show");

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')
    ->namespace('Admin')
    ->middleware('auth')
    ->name('admin.')
    ->group(function() {
        Route::get('/', 'Homecontroller@index')->name('index');
        Route::get('/categories', 'CategoryController@index')->name('categories.index');
        Route::get('/tags', 'TagController@index')->name('tags.index');
        // Route::get('/posts', 'PostController@index');
        Route::get("/post/filter", "PostController@filter")->name("posts.filter");
        Route::resource("/posts", "PostController");
        Route::resource("/users", "UserController");
    });
