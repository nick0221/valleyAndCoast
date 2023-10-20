<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front.home');
})->name('homepage');

Route::get('/contact-us', function () {
    return view('front.contact-us');
})->name('contactUs');


Route::get('/administrator', function () {
    return redirect(url('/admin'));
});


Route::get('/generate-pdf/{reference_id}', [\App\Http\Controllers\PDFController::class, 'generatePDF'])->name('print.invoice');
