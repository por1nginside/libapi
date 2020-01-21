<?php

use App\Book;
use App\Author;
use App\Category;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Author::class, 20)->create();
        factory(Category::class, 20)->create();
        factory(Book::class, 50)->create();

        $authors = Author::all();
        $categories = Category::all();

        Book::all()
            ->each(function ($book) use ($authors) {
                $book->authors()->attach(
                    $authors->random(rand(1, 3))->pluck('id')->toArray()
                );
            })
            ->each(function ($book) use ($categories) {
                $book->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
    }
}
