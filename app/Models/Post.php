<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    public $table = 'posts';

    public $fillable = [
        'title',
        'text',
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'title' => 'string',
        'text' => 'string',
        'user_id' => 'integer',
        'category_id' => 'integer'
    ];

    public static $rules = [
        'title' => 'required',
        'text' => 'required',
        'user_id' => 'required',
        'category_id' => 'required'
    ];

    /**
     * Get the comments of the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user that owns the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    

    /**
     * Get the category of the post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
