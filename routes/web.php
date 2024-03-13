<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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


Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/details', [ProductController::class, 'details'])->name('details');

Route::get('/category-products', [ProductController::class, 'categoryProduct'])->name('categoryProduct');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/add-to-cart', [CartController::class, 'addToCart'])->name('addToCart');
    Route::get('/my-cart', [CartController::class, 'myCart'])->name('myCart');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/recommanded', [ProductController::class, 'recomandedProuct'])->name('recomandedPrducts');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('updateProfile');
});
