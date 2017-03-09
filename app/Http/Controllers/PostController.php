<?php

namespace App\Http\Controllers;

use App\Post;
use Auth;
use App\Editor;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('post.show');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->isInRole('admin') || $user->isInRole('editor')){
            $this->validate($request, [
                'title' => 'required|max:255',
                'type' => 'required|in:1,2,3',
                'body' => 'required|max:65000',
            ]);
            $post = new Post();
            $post->title = $request->title;
            $post->type = $request->type;
            $post->body = $request->body;
            $post->is_open = 1;
            $post->user_id = Auth::id();
            $post->save();
            return redirect()->route('post.show', ['post' => $post->id]);
        }

        return redirect()->route('login');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $this->authorize('create', Post::class);
        return view('post.create');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function show($postId)
    {
        return view('post.show')->with(['postId' => $postId]);
    }

    public function byType($type)
    {
        return Post::where([
            'type' => $type,
            'is_open' => 1
        ]
        )->orderBy('updated_at', 'desc')->get();
    }

    public function byId($id)
    {
        return Post::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('post/edit')->withPost($post);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $post = Post::find($id);
        if($user->isInRole('admin') || $user->id === $post->user_id){
            $this->validate($request, [
                'title' => 'required|max:255',
                'type' => 'required|in:1,2,3',
                'body' => 'required|max:65000',
                'is_open' => 'required|in:0,1',
            ]);
            $post->title = $request->title;
            $post->type = $request->type;
            $post->body = $request->body;
            $post->is_open = $request->is_open;

            $post->save();
            return redirect()->route('post.show', ['post' => $id]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
