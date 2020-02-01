<?php

namespace App\Policies;

use App\Book;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    public function isOwner(User $user, Book $book)
    {
        return $user->is($book->author);
    }
}
