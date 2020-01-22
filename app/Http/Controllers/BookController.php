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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedColumns = [
            'id',
            'book',
        ];
        //search and sort on name book
        if ($request->has('book')) {
            $bookName = $request->book;
            $queryBook = Book::select($selectedColumns)
                ->where('book', 'like', '%' . $bookName . '%')
                ->orderBy('book', 'asc')
                ->paginate(5);
            if ($request->has('sort')) {
                $sort = $request->sort;
                $queryBookSort = Book::select($selectedColumns)
                    ->where('book', 'like', '%' . $request->book . '%')
                    ->orderBy('book', $sort)
                    ->paginate(5);
                return BookResource::collection($queryBookSort);
            }
            return BookResource::collection($queryBook);
        }
        //search and sort on author
        if ($request->has('author')) {
            $author = $request->author;
            $queryAuthor = Book::whereHas('authors', function ($query) use ($author) {
                $query->where('author', 'like', '%' . $author . '%');
            })
                ->orderBy('book', 'asc')
                ->paginate(5);
            if ($request->has('sort')) {
                $sortAuthors = $request->sort;
                $queryAuthorSort = Book::whereHas('authors', function ($query) use ($author) {
                    $query->where('author', 'like', '%' . $author . '%');
                })
                    ->orderBy('book', $sortAuthors)
                    ->paginate(5);
                return BookResource::collection($queryAuthorSort);
            }
            return BookResource::collection($queryAuthor);
        }
        //search and sort on categories
        if ($request->has('category')) {
            $category = $request->category;
            $queryCategory = Book::whereHas('categories', function ($query) use ($category) {
                $query->where('category', 'like', '%' . $category . '%');
            })
                ->orderBy('book', 'asc')
                ->paginate(5);
            if ($request->has('sort')) {
                $sortCategory = $request->sort;
                $queryCategorySort = Book::whereHas('categories', function ($query) use ($category) {
                    $query->where('category', 'like', '%' . $category . '%');
                })
                    ->orderBy('book', $sortCategory)
                    ->paginate(5);
                return BookResource::collection($queryCategorySort);
            }
            return BookResource::collection($queryCategory);
        }

        $book = Book::select($selectedColumns)
            ->paginate(5);

        return BookResource::collection($book);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->categories()->sync($request->categories);
        $book->save();

        return new BookResource($book);
    }
}
