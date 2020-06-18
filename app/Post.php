<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Like;
use App\User;

class Post extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
