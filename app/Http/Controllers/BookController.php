<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request)
    {
        if (empty($request->all())) {
            $book = Book::paginate(5);

            return BookResource::collection($book);
        }
        //search and sort on name book
        if ($request->has('book')) {
            $bookName = $request->book;
            $sortBookName = $request->sort;
            $queryBook = Book::bookSearch($bookName, $sortBookName);

            return BookResource::collection($queryBook);
        }
        //search and sort on author
        if ($request->has('author')) {
            $author = $request->author;
            $sortAuthors = $request->sort;
            $queryAuthor = Book::bookWithAuthor($author, $sortAuthors);

            return BookResource::collection($queryAuthor);
        }
        //search and sort on categories
        if ($request->has('category')) {
            $category = $request->category;
            $sortCategory = $request->sort;
            $queryCategory = Book::bookWithCategory($category, $sortCategory);

            return BookResource::collection($queryCategory);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(BookRequest $request)
    {
        $book = new Book;
        $book->book = $request->book;
        $book->save();
        $book->categories()->attach($request->categories);
        $book->authors()->attach($request->authors);

        return new BookResource($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book $book
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Book $book
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->categories()->sync($request->categories);
        $book->save();

        return new BookResource($book);
    }
}
