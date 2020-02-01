<?php

namespace Tests\Feature;

use App\Book;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_auth_user_can_create_book()
    {
        $bookData = factory(Book::class)->raw(['author_id' => '']);
        $this
            ->json('POST', '/api/v1/books', $bookData)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_user_can_create_book()
    {
        $author = factory(User::class)->create();

        $bookData = factory(Book::class)->raw(['author_id' => '']);

        $response = $this->actingAs($author, 'api')
            ->post('api/v1/books', $bookData);

        $response->assertCreated();

//        dd($response->json());

        $response->assertJson([
            'status' => "success",
            'status_code' => 201,
            'data' => [
                'book' => [
                    'name' => $bookData['name'],
                    'isbn' => $bookData['isbn'],
                    'country' => $bookData['country'],
                    'number_of_pages' => $bookData['number_of_pages']
                ]
            ]
        ]);

        $book = Book::first();
        $this->assertEquals($bookData['name'], $book->name);
        $this->assertEquals($bookData['isbn'], $book->isbn);
        $this->assertEquals($bookData['country'], $book->country);
    }

    public function test_book_fields_are_required()
    {
        $author = factory(User::class)->create();

        $bookData = factory(Book::class)
            ->raw([
                'author_id' => $author->getKey(),
                'name' => '',
                'isbn' => '',
                'country' => '',
                'number_of_pages' => '',
                'publishers' => '',
            ]);

        $this->actingAs($author, 'api')
            ->post('api/v1/books', $bookData)
            ->assertSessionHasErrors([
                'name',
                'isbn',
                'country',
                'number_of_pages',
                'publishers'
            ]);
    }

    public function test_to_list_all_books()
    {
        $bookCreate = factory(Book::class,4)->create();

        $response = $this->get('api/v1/books');

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'status_code' => 200,
            'data' => [
                [
                    'name' => $bookCreate[0]['name'],
                    'isbn' => $bookCreate[0]['isbn'],
                    'publishers' => $bookCreate[0]['publishers'],
                    'country' => $bookCreate[0]['country']
                ],
                [
                    'name' => $bookCreate[1]['name'],
                    'isbn' => $bookCreate[1]['isbn'],
                ],
                [
                    'name' => $bookCreate[2]['name'],
                    'isbn' => $bookCreate[2]['isbn'],
                ]
            ]
        ]);

        $books = Book::get();
        $this->assertCount(4, $books);
        $this->assertEquals($bookCreate[0]->name, $books[0]->name);
        $this->assertEquals($bookCreate[0]->isbn, $books[0]->isbn);
        $this->assertEquals($bookCreate[1]->name, $books[1]->name);
        $this->assertEquals($bookCreate[1]->isbn, $books[1]->isbn);
        $this->assertEquals($bookCreate[3]->isbn, $books[3]->isbn);
        $this->assertEquals($bookCreate[3]->country, $books[3]->country);

    }

    public function test_to_list_empty_books()
    {
        $response = $this->get('api/v1/books');
        $response->assertJson([
            'status' => 'success',
            'status_code' => 200,
            'data' => []
        ]);
        $books = Book::get();
        $this->assertCount(0, $books);
    }

    public function test_to_search_for_books_by_name()
    {

        $bookToSearch = factory(Book::class)->create(['name' => 'The gods are crazy']);

        factory(Book::class, 5)->create();

        $bookToSearchName = "The gods are crazy";

        $response = $this->get("api/v1/books/?name={$bookToSearchName}");

        $response->assertJson(['data' => [
            [
                'name' => $bookToSearch->name,
            ]
        ]]);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_to_search_for_books_by_country()
    {

        $bookToSearch = factory(Book::class)->create(['country' => 'Nigeria']);

        factory(Book::class, 5)->create();

        $bookToSearchByCountry = "Nigeria";

        $response = $this->get("api/v1/books/?country={$bookToSearchByCountry}");

        $response->assertJson(['data' => [
            [
                'country' => $bookToSearch->country,
            ]
        ]]);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_to_search_for_books_by_publisher()
    {
        $bookToSearch = factory(Book::class)->create(['publishers' => 'Press Avenue']);

        factory(Book::class, 5)->create();

        $bookToSearchByPublishers = "Press Avenue";

        $response = $this->get("api/v1/books/?publisher={$bookToSearchByPublishers}");

        $response->assertJson(['data' => [
            [
                'publishers' => $bookToSearch->publishers,
            ]
        ]]);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_to_search_for_books_by_year()
    {

        factory(Book::class)->create(['release_date' => now()->subYears(5)]);

        factory(Book::class, 5)->create();


        $bookToSearchByYear = "2015";

        $response = $this->get("api/v1/books/?year={$bookToSearchByYear}");

        $this->assertCount(1, $response->json('data'));
    }

    public function test_user_can_update_book()
    {
        $this->withoutExceptionHandling();

        $author = factory(User::class)->create();

        $book = factory(Book::class)->create(['author_id' => $author->getKey()]);

        $bookData = factory(Book::class)->raw([
            'name' => 'Update of Book Name',
            'isbn' => '1111-2222-3333-4444',
            'country' => 'Nigeria',
            'number_of_pages' => 140,
        ]);

        $response = $this->actingAs($author, 'api')
            ->patch("api/v1/books/{$book->getKey()}", $bookData);


        $response->assertJson([
            'status' => 'success',
            'status_code' => 200,
            'message' => "The book {$bookData['name']} was updated successfully.",
            'data' => [
                'book' => [
                    'name' => $bookData['name'],
                    'isbn' => $bookData['isbn'],
                    'number_of_pages' => $bookData['number_of_pages']
                ]
            ]
        ]);

        $bookUpdate = Book::first();

        $this->assertEquals($bookData['name'], $bookUpdate->name);
        $this->assertEquals($bookData['isbn'], $bookUpdate->isbn);
        $this->assertEquals($bookData['number_of_pages'], $bookUpdate->number_of_pages);
        $this->assertEquals($bookData['country'], $bookUpdate->country);
    }

    public function test_only_book_owner_can_update_book()
    {

        $unauthorizedAuthor = factory(User::class)->create();

        $bookAuthor = factory(User::class)->create();
        $book = factory(Book::class)->create(['author_id' => $bookAuthor->getKey()]);

        $bookData = factory(Book::class)->raw([
            'name' => 'Update of Book Name',
            'isbn' => '1111-2222-3333-4444',
            'country' => 'Nigeria',
            'number_of_pages' => 140,
        ]);

        $response = $this->actingAs($unauthorizedAuthor, 'api')
            ->patch("api/v1/books/{$book->getKey()}", $bookData);

        $response->assertForbidden();
    }

    public function test_user_can_delete_book()
    {
        $this->withoutExceptionHandling();

        $author = factory(User::class)->create();

        $book = factory(Book::class)->create(['author_id' => $author->getKey()]);

        $response = $this->actingAs($author, 'api')->delete("api/v1/books/{$book->getKey()}");
        $response->assertJson([
            'status_code' => 204,
            'status' => 'success',
            'message' => "The book {$book->name} was deleted successfully"
        ]);

        $books = Book::all();
        $this->assertCount(0, $books);
    }

    //Todo test_only_book_owner_can_delete_book
    public function test_only_book_owner_can_delete_book()
    {
//        $this->withoutExceptionHandling();

        $bookOwner = factory(User::class)->create();
        $notBookOwer = factory(User::class)->create();

        $book = factory(Book::class)->create(['author_id' => $bookOwner->getKey()]);

        $response = $this->actingAs($notBookOwer, 'api')->delete("api/v1/books/{$book->getKey()}");

        $response->assertForbidden();
    }

    public function test_to_show_book_details()
    {
        $book = factory(Book::class)->create();

        $response = $this->get("api/v1/books/{$book->getKey()}");

        $response->assertOk();

        $bookShow = Book::first();

        $this->assertEquals($book->getKey(), $bookShow->getKey());
        $this->assertEquals($book->name, $bookShow->name);
    }
}
