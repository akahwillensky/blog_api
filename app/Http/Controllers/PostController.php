<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->image = $request->image;
        $post->user_id = Auth::guard('api')->user()->id;
        $post->save();

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $this->validate($request, [
            'title' => 'string|max:255',
            'description' => 'string',
            'image' => 'required|string',
        ]);

        $post->title = $request->title;
        $post->description = $request->description;
        $post->image = $request->image;
        $post->user_id = Auth::guard('api')->user()->id;
        $post->save();

        return response()->json($post);
    }
    public function read(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        
        $post->user_id = Auth::guard('api')->user()->id;
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
