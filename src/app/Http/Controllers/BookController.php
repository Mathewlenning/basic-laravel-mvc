<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Book;
use Throwable;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Book    $books
     *
     * @return View
     */
    public function index(Request $request, Book $books)
    {
        $requestData = $this->getRequestData($request);

        $books = $books->getList($requestData);

        $requestData['books'] = $books;

        return view('booklist', $requestData);
    }


    protected function getRequestData(Request $request)
    {
        return ['list'   => $request->input('list', $request->old('list', ['order_by' => 'book_title', 'direction' => 'ASC'])),
         'search' => $request->input('search', $request->old('search')),
         'message' => Session::get('message'),
         'message_type' => Session::get('message_type')];
    }

    /**
     * Create or update a book.
     *
     * @param Request $request
     * @param Book    $books
     *
     * @return RedirectResponse
     */
    public function store(Request $request, Book $books)
    {
        $addForm = $request->input('addform');

        $message = '';
        $message_type = 'success';

        if (!empty($addForm['data'])) {
            $message = 'Record Created';
            try {
                if (!empty($addForm['data']['book_id'])) {
                    $book = $books->find($addForm['data']['book_id']);

                    $book->update($addForm['data']);
                } else {
                    $books->create($addForm['data']);
                }
            } catch (Throwable $e) {
                $message = $this->getErrorMessage($e);
                $message_type = 'danger';
            }
        }

        Session::flash('message', $message);
        Session::flash('message_type', $message_type);

        return redirect('/')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Book    $book
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, Book $book)
    {
        $message = 'Record Deleted';
        $message_type = 'success';

        try {
            $book->find($request->input('book_id'))->delete();
        } catch (Throwable $e) {
            $message = $this->getErrorMessage($e);
            $message_type = 'danger';
        }

        Session::flash('message', $message);
        Session::flash('message_type', $message_type);

        return redirect('/')->withInput();
    }

    /**
     * Method to export csv or xml files
     *
     * @param Request $request
     * @param Book    $book
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
     */
    public function export(Request $request, Book $book)
    {
        $export = $request->input('export');

        // We can't export a file with no columns
        if (empty($export['columns'])) {
            Session::flash('message', 'Export failed. Please choose at least one column to export.');
            Session::flash('message_type', 'error');
            return redirect('/')->withInput();
        }

        // We only allow two format types ATM
        if (!in_array($export['format'], ['XML', 'CSV'])) {
            Session::flash('message', 'Export failed. Unsupported format requested.');
            Session::flash('message_type', 'error');

            return redirect('/')->withInput();
        }

        if (in_array('authors_name', $export['columns'])) {
            // First we need to get rid of the placeholder value
            $columns = array_flip($export['columns']);
            unset($columns['authors_name']);

            // Now we add the actual columns names
            $export['columns'] = array_flip($columns);
            $export['columns'][] = 'author_first_name';
            $export['columns'][] = 'author_last_name';
        }

        $books = $book->getList($this->getRequestData($request));

        $templateName = 'booklist_' . strtolower($export['format']);

        $content = view($templateName, ['export' => $export, 'books' => $books->toArray()]);

        $fileName = 'BookFace-export-'.now()->format('Y-m-d') .'.'. strtolower($export['format']);

        return response($content)
            ->header('Content-Type', 'text/' . strtolower($export['format']))
            ->header('Content-Disposition', 'attachement; filename=' . $fileName);
    }

    /**
     * Method to strip sensitive database details from return error messages.
     *
     * @param Throwable $e throw exception
     *
     * @return string
     */
    protected function getErrorMessage(Throwable $e)
    {
        $errorMessage = $e->getMessage();

        if (strpos($errorMessage, 'SQLSTATE') === false) {
            return $errorMessage;
        }

        $start = strlen('SQLSTATE['.$e->getCode().']:');
        $length = strpos($errorMessage, '(') - $start;

        $message = substr(
            $errorMessage,
            $start,
            $length
        );

        if (empty($message)) {
            return 'Unknown error has occurred. Please contact the administrator for futher assistance.';
        }

        return $message;
    }
}
