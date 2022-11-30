<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BookController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(BookController::class)->group(function()
{
    // Show/Sort/Search the list
    Route::get('/', 'index');

    // Add new book/author
    Route::post('/', 'store');

    // Delete a book
    Route::delete('/', 'destroy');

    // Export csv/xml
    Route::post('/export', 'export');
});

