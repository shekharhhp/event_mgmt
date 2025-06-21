<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\TalkProposalController;

Route::middleware(['auth'])->group(function () {
    Route::get('/talks', [TalkProposalController::class, 'index'])->name('talks.index');
    Route::get('/talks/create', [TalkProposalController::class, 'create'])->name('talks.create');
    Route::post('/talks', [TalkProposalController::class, 'store'])->name('talks.store');
    Route::get('/talks/{talk}/edit', [TalkProposalController::class, 'edit'])->name('talks.edit');
    Route::put('/talks/{talk}', [TalkProposalController::class, 'update'])->name('talks.update');
});

