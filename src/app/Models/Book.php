<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Book extends Model
{
    use HasFactory;

    /**
     * Custom primary key name for this table
     *
     * @var string
     */
    protected $primaryKey = 'book_id';

    /**
     * Allow mass assignment for the book title.
     *
     * @var string[]
     */
    protected $fillable = ['book_title', 'author_first_name', 'author_last_name'];


    /**
     * Method to get a searchable list of books
     *
     * @param array $requestData e.g. ['list' => ['order_by'=>'book_title','direction'=>'ASC'],'search' => '']
     *
     * @return Collection
     */
    public function getList(Array $requestData)
    {
        $model = new Book();
        $listFilters = $model->getValidListFilters($requestData['list']);

        return $model
            ->search($requestData['search'])
            ->orderBy($listFilters['order_by'], $listFilters['direction'])
            ->get();
    }

    /**
     * Method to add search to the query
     *
     * @param Builder $query
     * @param string  $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, $search)
    {
        if (empty($search))
        {
            return $query;
        }

        $searchFilter = self::getCleanSearch($search);

        foreach ($searchFilter as $index => $value)
        {
            if ($index == 0)
            {
                $query->where('book_title','LIKE', '%' . $value . '%');
                $query->orWhere('author_first_name', 'LIKE', '%' . $value . '%');
                $query->orWhere('author_last_name', 'LIKE', '%' . $value . '%');
            }

            $query->orWhere('book_title','LIKE', '%' . $value . '%');
            $query->orWhere('author_first_name', 'LIKE', '%' . $value . '%');
            $query->orWhere('author_last_name', 'LIKE', '%' . $value . '%');
        }

        return $query;
    }
    /**
     * Validate the user input before using it in the query
     *
     * @param array $listFilters
     *
     * @return array
     */
    public function getValidListFilters(Array $listFilters)
    {
        // we only allow sorting on the book title and author last name field
        $listFilters['order_by'] = (!in_array($listFilters['order_by'], ['book_title', 'author_last_name']))? 'book_title': $listFilters['order_by'];

        //Make sure we have a valid direction value
        $listFilters['direction'] = strtoupper($listFilters['direction']);
        $listFilters['direction'] = (!in_array($listFilters['direction'], ['ASC', 'DESC']))  ? 'ASC' : $listFilters['direction'];

        return $listFilters;
    }

    /**
     * Method to prepare the search string for the query
     *
     * @param mixed $search an array or string
     *
     * @return array
     */
    protected function getCleanSearch($search)
    {

        // Check if we're dealing with a string with spaces
        if (!is_array($search))
        {
            if (strpos($search,' ') === false)
            {
                return array($search);
            }

            $search = explode(' ', $search);

            // this is probably a book title, so put it back together again.
            if (count($search) < 2)
            {
                return array(implode(' ',$search));
            }
        }

        $cleanSearch = array();
        foreach ($search AS $value)
        {
            $cleanSearch += self::getCleanSearch($value);
        }

        return $cleanSearch;
    }
}
