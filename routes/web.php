<?php

use App\Http\Controllers\AcknowledgmentReceipt;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFController;
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

//Route::get('/', function () {
//    return view('front.home');
//})->name('homepage');
Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::get('/contact-us', function () {
    return view('front.contact-us');
})->name('contactUs');

Route::get('/administrator', function () {
    return redirect(url('/admin'));
});

Route::get('/view-accommodation/{id}', [HomeController::class, 'view'])->name('view.accommodation');
Route::get('/accommodations', [HomeController::class, 'accommodationList'])->name('view.list.accommodation');
Route::post('/inquiries', [HomeController::class, 'inquiries'])->name('send.inquiries');


Route::get('/thank-you', function () {
    return view('front.thank-you');
})->name('thanks.message');



Route::get('/generate-pdf/{reference_id}', [PDFController::class, 'generatePDF'])->name('print.invoice');
Route::get('/generate-receipt/{reference_id}', [AcknowledgmentReceipt::class, 'generatePDF'])->name('print.receipt');
