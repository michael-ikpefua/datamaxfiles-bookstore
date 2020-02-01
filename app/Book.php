<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name', 'isbn', 'author_id', 'country', 'number_of_pages', 'publishers', 'release_date'
    ];

    protected $dates = [
        'release_date'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @param $query
     * @param null $name
     * @return mixed
     */
    public function scopeWhereName($query, $name = null)
    {
        if(!$name) return $query;

        return $query->where("name", $name);
    }

    /**
     * @param $query
     * @param null $country
     * @return mixed
     */
    public function scopeWhereCountry($query, $country = null)
    {
        if(!$country) return $query;

        return $query->where('country', $country);
    }

    public function scopeWherePublisher($query, $publisher = null)
    {
        if(!$publisher) return $query;

        return $query->where('publishers', $publisher);
    }

    public function scopeWhereReleaseYear($query, $year = null)
    {
        if(!$year) return $query;

        return $query->whereYear('release_date', $year);
    }
}
