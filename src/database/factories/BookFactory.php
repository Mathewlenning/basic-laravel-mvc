<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    /**
     * Method to create one book record
     *
     * @return Book
     */
    public function createTestBook()
    {
        return Book::factory()->create(['book_title' => 'Pride and Prejudice','author_first_name' => 'Jane', 'author_last_name' => 'Austen']);
    }

    /**
     * Methode to create a few book records
     * @return Book
     */
    public function createTestBookList()
    {
        Book::factory()->create(['book_title' => 'Pride and Prejudice','author_first_name' => 'Jane', 'author_last_name' => 'Austen']);
        Book::factory()->create(['book_title' => 'Alice\'s Adventures in Wonderland','author_first_name' => 'Lewis', 'author_last_name' => 'Carroll']);
        $returnBook = Book::factory()->create(['book_title' => 'Adventures of Tom Sawyer','author_first_name' => 'Mark', 'author_last_name' => 'Twain']);

        return $returnBook;
    }
}
