<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/details', [ProductController::class, 'details'])->name('details');

Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('addToCart');