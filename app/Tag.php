<?php

namespace App;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'mobile_tags';
    protected $fillable = ['name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
