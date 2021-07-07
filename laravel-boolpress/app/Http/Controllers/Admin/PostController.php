<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $incomingData = session("posts");

        if (isset($incomingData)) {
            $data =  [
                "posts" => $incomingData
            ];
        } else {
            $data = [
                'posts' => Post::orderBy("created_at", "DESC")
                ->where("user_id", $request->user()->id)
                ->get()
            ];
        }
        
        return view("admin.posts.index", $data);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view("admin.posts.create", ["categories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        $formData = $request->all();
        $newPost = new Post();
        $newPost->fill($formData);

        $newPost->user_id = $request->user()->id;

        // genero lo slug
        $slug = Str::slug($newPost->title);
        $slugBase = $slug;

        // verifico che lo slug non esista nel database
        $postExists = Post::where('slug', $slug)->first();
        $contatore = 1;

        // entro nel ciclo while se ho trovato un post con lo stesso $slug
        while ($postExists) {
            // genero un nuovo slug aggiungendo il contatore alla fine
            $slug = $slugBase . '-' . $contatore;
            $contatore++;
            $postExists = Post::where('slug', $slug)->first();
        }

        // quando esco dal while sono sicuro che lo slug non esiste nel db
        // assegno lo slug al post
        $newPost->slug = $slug;

        $newPost->save();
        return redirect()->route('admin.posts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', [
            "post" => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        $data = [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags
        ];

        return view('admin.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => "nullable|exists:categories,id",
            'tags' => "exists:tags,id"
        ]);

        $formData = $request->all();

        if ($formData['title'] != $post->title) {
            
            $slug = Str::slug($formData['title']);
            $slugBase = $slug;
            
            $postExists = Post::where('slug', $slug)->first();
            $contatore = 1;
            
            while ($postExists) {
                
                $slug = $slugBase . '-' . $contatore;
                $contatore++;
                $postExists = Post::where('slug', $slug)->first();
            }
            
            $formData['slug'] = $slug;
        }

        $post->tags()->detach();
        $post->tags()->attach($formData['tags']);

        $post->update($formData);

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post) {
        $post->delete();
        return redirect()->route('admin.posts.index');
    }

    public function filter(Request $request) {
        $filters = $request->all();

        // $posts = Post::with(["tag" => function ($query) use ($filters) {
        //     $query->where("id", 2);
        // }])->get();

        $posts = Post::join("post_tag", "post_id", "=", "post_tag.post_id")->where("post_tag.tag_id", $filters["tag"])->get();

        return redirect()->route("admin.posts.index")->with(["posts" => $posts]);
    }
}
