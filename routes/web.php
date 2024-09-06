<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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
//Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
//Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
//Route::post('login', [LoginController::class, 'login']);





Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Apply the 'auth' middleware to protect the charge route
Route::post('/products/{id}/charge', [ProductController::class, 'charge'])
    ->middleware('auth')
    ->name('products.charge');

    Route::get('/products1/{id}', [ProductController::class, 'show1'])->name('products.show1');


    Route::post('/products/{id}/charge1', [ProductController::class, 'charge1'])
    ->middleware('auth')
    ->name('products.charge1');