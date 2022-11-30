<?php

namespace Tests\Feature;

use Database\Factories\BookFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Book;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to make the index page is working right.
     *
     * @return void
     */
    public function test_index()
    {
        // Get the index
        $response = $this->get('/');

        // We expect it to be a success
        $response->assertStatus(200);
    }

    public function test_store_create_successful()
    {
        $response = $this->post(
            '/',
            ['addform'=>
                 ['data' =>
                    ['book_title' => 'Pride and Prejudice',
                    'author_first_name' => 'Jane',
                    'author_last_name' => 'Austen',
                    'book_id' => ''
                    ]
                ]
            ]);

        $response->assertRedirect('/');

        // Now we make sure we have a book record
        $book = Book::all()->first();
        $this->assertNotEmpty($book->book_id);
    }

    public function test_store_update_successful()
    {
        $book = Book::factory()->createTestBook();

        // Now we update.
        $response = $this->post(
            '/',
            ['addform'=>
                 ['data' =>
                      ['book_title' => 'Alice\'s Adventures in Wonderland',
                       'author_first_name' => 'Jane',
                       'author_last_name' => 'Austen',
                       'book_id' => $book->book_id
                      ]
                 ]
            ]);

        $response->assertRedirect('/');

        // Now we make sure the record was updated.
        $updated = $book->fresh();
        $this->assertEquals('Alice\'s Adventures in Wonderland', $updated->book_title);

    }

    public function test_store_fail()
    {
        $response = $this->post(
            '/',
            ['addform'=>
                 ['data' =>
                      ['book_title' => 'Pride and Prejudice',
                       'author_first_name' => 'Jane',
                       'author_last_name' => '',
                       'book_id' => ''
                      ]
                 ]
            ]);

        $response->assertRedirect('/');

        // Now we make sure we don't have a book record
        $book = Book::all()->first();

        $this->assertEmpty($book);
    }

    public function test_destroy_successful()
    {
        $book = Book::factory()->createTestBook();

        $response = $this->delete('/', ['book_id'=> $book->book_id]);

        $response->assertRedirect('/');

        // Now we make sure we don't have a book record
        $book = Book::all()->first();

        $this->assertEmpty($book);
    }


    public function test_export_xml()
    {
        $book = Book::factory()->createTestBook();

        $response = $this->post(
            '/export',
            ['export'=>
                 [
                     'columns' => ['book_title', 'authors_name'],
                     'format' => 'XML'
                 ]
            ]);

        $response->assertHeader('content-type','text/xml; charset=UTF-8');

        $exception = false;

        try
        {
            $xml = simplexml_load_string($response->content());
        }
        catch (\Throwable $e)
        {
            $exception = $e;
        }

        // if we render the content in simple xml without an exception
        // then we know it is a valid xml response.
        $this->assertFalse($exception);

        $this->assertEquals('Pride and Prejudice', $xml->record[0]->book_title);
        $this->assertEquals('Jane', $xml->record[0]->author_first_name);
        $this->assertEquals('Austen', $xml->record[0]->author_last_name);
    }

    public function test_export_csv()
    {
        $book = Book::factory()->createTestBook();

        $response = $this->post(
            '/export',
            ['export'=>
                 [
                     'columns' => ['book_title', 'authors_name'],
                     'format' => 'CSV'
                 ]
            ]);

        $response->assertHeader('content-type','text/csv; charset=UTF-8');

        // Now we use a base64_encoded value to compare the response content.
        $expected = 'VGl0bGUsRmlyc3QgTmFtZSxMYXN0IE5hbWUNCiJQcmlkZSBhbmQgUHJlanVkaWNlIiwiSmFuZSIsIkF1c3RlbiINCg==';
        $this->assertEquals($expected, base64_encode($response->content()));
    }
}
