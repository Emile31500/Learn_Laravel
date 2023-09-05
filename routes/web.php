<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('accueil', [ArticleController::class, 'index']);

Route::get('/sign', [AuthController::class, 'sign'])->name('sign');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/sign', [AuthController::class, 'signPost'])->name('sign.post');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/article/ajouter', [ArticleController::class, 'add'])->name('article.add');
Route::post('/article/ajouter', [ArticleController::class, 'addPost'])->name('article.add.post');

