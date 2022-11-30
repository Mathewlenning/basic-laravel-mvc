<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;

class BookModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Method to build a fake requestData array
     *
     * @param string $search
     * @param string $orderBy
     * @param string $direction
     *
     * @return array
     */
    protected function fakeRequestData($search = '', $orderBy = 'book_title', $direction = 'ASC')
    {
        return [
            'list' => [
                'order_by' => $orderBy,
                'direction' => $direction],
            'search' => $search
        ];
    }

    /**
     * Test the default get list results.
     */
    public function test_get_list()
    {
        // create some books to test on
        $book = Book::factory()->createTestBookList();

        $requestData = $this->fakeRequestData();
        $list = $book->getList($requestData);


        // We should get all the books in the list
        $this->assertEquals(3, $list->count());
    }

    public function test_get_list_sorted_by_authors()
    {
        // create some books to test on
        $book = Book::factory()->createTestBookList();

        $requestData = $this->fakeRequestData('', 'author_last_name');
        $list = $book->getList($requestData);

        $this->assertEquals('Austen', $list->first()->author_last_name);
    }

    public function test_get_list_search_by_book_title()
    {
        // create some books to test on
        $book = Book::factory()->createTestBookList();

        $requestData = $this->fakeRequestData('Wonderland');
        $list = $book->getList($requestData);

        $this->assertEquals(1, $list->count());
        $this->assertEquals('Alice\'s Adventures in Wonderland', $list->first()->book_title);
    }

    public function test_get_list_search_by_author_name()
    {
        // create some books to test on
        $book = Book::factory()->createTestBookList();

        // Add one more book to make sure we're just not getting the first record from that author.
        $book->create(['book_title' => 'The Adventures of Huckleberry Finn', 'author_first_name' => 'Mark', 'author_last_name' => 'Twain']);

        $requestData = $this->fakeRequestData('Mark Twain');
        $list = $book->getList($requestData);

        $this->assertEquals(2, $list->count());
        $this->assertEquals('Adventures of Tom Sawyer', $list->first()->book_title);
    }

    public function test_get_valid_filters()
    {
        $book = Book::factory()->make();

        $listFilters = [
            'order_by' => 'unacceptable_value',
            'direction' => 'desc'];

        $returnedFilters = $book->getValidListFilters($listFilters);

        $this->assertEquals('book_title', $returnedFilters['order_by']);
        $this->assertEquals('DESC', $returnedFilters['direction']);
    }
}
