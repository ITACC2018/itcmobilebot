<?php

namespace App;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'mobile_categories';
    protected $fillable = ['name'];

    protected static function boot()
    {
        static::deleting(function ($category) {
            $category->posts()->delete();
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
