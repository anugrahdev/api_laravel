<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // foreach ($request as $post) {
        //     $post->user;
        //     $post['commentsCount'] = count($post->comments);
        //     $post['likesCount'] = count($post->likes);
        //     $post['selfLike'] = false;
        //     foreach ($post->likes as $like) {
        //         if ($like->user_id == Auth::user()->id) {
        //             $post['selfLike'] = true;
        //         }
        //     }
        // }

        return parent::toArray($request);
    }
}
