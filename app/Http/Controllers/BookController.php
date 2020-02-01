<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\BookRequest;
use App\Http\Resources\Book as BookResource;
use App\Http\Resources\Books;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public $model;

    public function __construct(Book $model)
    {
        $this->model = $model;

        $this->middleware("can:isOwner,book")->only(['update', 'destroy']);
    }

    public function index()
    {
        $books = $this->model
            ->whereName(request('name'))
            ->whereCountry(request('country'))
            ->wherePublisher(request('publisher'))
            ->whereReleaseYear(request('year'))
            ->with('author')->get();

        return new Books($books);
    }

    public function store(BookRequest $request)
    {
        $bookData = $this->bookData($request);

        $book = $this->user()->books()->create($bookData);

        $book->load('author');

        return (new BookResource($book))
            ->additional([
                'status_code' => 201,
                'status' => 'success',
            ]);

    }

    public function show(Book $book)
    {
        $book->load('author');

        return (new BookResource($book))
            ->additional([
                'status_code' => 200,
                'status' => 'success',
            ]);
    }

    public function update(Book $book, BookRequest $request)
    {
        $bookData = $this->bookData($request);

        $book->update($bookData);

        return (new BookResource($book))
            ->additional([
                'message' => "The book {$book->name} was updated successfully.",
                'status_code' => 200,
                'status' => 'success'
                ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()
            ->json([
                'status_code' => 204,
                'status' => 'success',
                'message' => "The book {$book->name} was deleted successfully",
                'data' => []
            ]);
    }

    protected function bookData($request)
    {
        return
            $request
                ->only(['name', 'isbn', 'country', 'number_of_pages', 'publishers', 'release_date']);
    }

}
