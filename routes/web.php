<?php

use App\Http\Controllers\ProfileController;
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
    return view('Frontend.home');
});

Route::get('/about', function () {
    return view('Frontend.about');
});

Route::get('/shop', function () {
    return view('Frontend.shop');
});

Route::get('/contact', function () {
    return view('Frontend.contact');
});

Route::get('/privacy-policy', function () {
    return view('Frontend.privacy-policy');
});

Route::get('/terms-and-condition', function () {
    return view('Frontend.terms-and-condition');
});

Route::get('/return-policy', function () {
    return view('Frontend.return-policy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
require __DIR__ . '/frontend.php';
