<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'author',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
