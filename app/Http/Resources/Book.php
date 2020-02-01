<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Book as BookModel;

/*
 * Class Book
 * @package App\Http\Resources
 * @mixin BookModel
 */

class Book extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'book' => [
                'name' => $this->name,
                'isbn' => $this->isbn,
                'authors' => [$this->author->name],
                'number_of_pages' => $this->number_of_pages,
                'publishers' => $this->publishers,
                'country' => $this->country,
                'release_date' => $this->release_date->format('YYYY-MM-DD'),
            ],
        ];
    }
}
