<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Book;

/**
 * Class Books
 * @package App\Http\Resources
 */

class Books extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'status_code' => 200,
                'status' => 'success',

            ] +
            [
                'data' =>
                    $this->collection->map(function (Book $book) {
                        return
                            [
                                'name' => $book->name,
                                'isbn' => $book->isbn,
                                'authors' => [$book->author->name],
                                'number_of_pages' => $book->number_of_pages,
                                'publishers' => $book->publishers,
                                'country' => $book->country,
                                'release_date' => $book->release_date->format('YYYY-MM-DD'),
                            ];
                    })->all()

            ];
    }
}
