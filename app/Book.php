<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'book',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public static function scopeBookSearch($query, $bookName, $sortBookName)
    {
        return $query
            ->where('book', 'like', '%' . $bookName . '%')
            ->orderBy('book', $sortBookName)
            ->paginate(5);
    }

    public static function scopeBookWithAuthor($query, $author, $sort)
    {
        return $query
            ->whereHas('authors', function ($query) use ($author) {
                $query->where('author', 'like', '%' . $author . '%');
            })
            ->orderBy('book', $sort)
            ->paginate(5);
    }

    public static function scopeBookWithCategory($query, $category, $sort)
    {
        return $query
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('category', 'like', '%' . $category . '%');
            })
            ->orderBy('book', $sort)
            ->paginate(5);
    }

}
