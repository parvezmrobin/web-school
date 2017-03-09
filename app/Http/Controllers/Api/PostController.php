<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Auth;
use App\Editor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function byType(Request $reuqest, $type)
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
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $user = $request->user();
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
            return response()->json($post);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $user = $reuqest->user();
        $post = Post::find($id);
        if(Auth::user()->isInRole('admin') || $user->id === $post->user_id){
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
            return response()->json($post);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, $id)
    {
        $user = $reuqest->user();
        $post = Post::find($id);
        if(Auth::user()->isInRole('admin') || $user->id === $post->user_id){
            $post->delete();
            return response()->json(["status" => "succeeded"]);;
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
