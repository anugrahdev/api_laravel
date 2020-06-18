<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{

    public function index()
    {
        $posts = Post::latest()->paginate(3);
        foreach ($posts as $post) {
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;
            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }


        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::find($id);
        // $post->user;
        // $post['commentsCount'] = count($post->comments);
        // $post['likesCount'] = count($post->likes);
        // $post['selfLike'] = false;
        // foreach ($post->likes as $like) {
        //     if ($like->user_id == Auth::user()->id) {
        //         $post['selfLike'] = true;
        //     }
        // }
        $post->user;
        $post->commentsCount = count($post->comments);
        $post->likesCount = count($post->likes);
        $post->selfLike = false;
        foreach ($post->likes as $like) {
            if ($like->user_id == Auth::user()->id) {
                $post->selfLike = true;
            }
        }
        return new PostResource($post);
    }


    public function create(Request $request)
    {
        $post = new Post();

        $post->user_id = Auth::user()->id;
        $post->caption = $request->caption;

        //check if post have aphoto
        if ($request->photo  != '') {
            //choose a unique name for photo
            $photo = time() . 'jpg';
            file_put_contents('storage/posts/' . $photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;
        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => new PostResource($post)
        ]);
    }



    public function update(Request $request, Post $post)
    {
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ]);
        }


        $post->update([
            'caption' => $request->caption
        ]);

        return response()->json([
            'success' => true,
            'data' => new PostResource($post)
        ]);
    }

    public function delete(Post $post)
    {
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ]);
        }

        if ($post->photo != '') {
            Storage::delete('public/posts/' . $post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Post Deleted',
        ]);
    }
}
