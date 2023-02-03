<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id'];

    protected $appends = ['author_name'];

    public function authorName(): Attribute
    {
        return new Attribute(
            get: fn () =>  $this->author()->first()->name
        );
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
