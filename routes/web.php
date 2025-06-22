<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalkProposalController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Optional: remove if not using HomeController
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Speaker routes (talk proposals)
Route::middleware(['auth'])->group(function () {
    Route::get('/talks', [TalkProposalController::class, 'index'])->name('talks.index');
    Route::get('/talks/create', [TalkProposalController::class, 'create'])->name('talks.create');
    Route::post('/talks', [TalkProposalController::class, 'store'])->name('talks.store');
    Route::get('/talks/{talk}/edit', [TalkProposalController::class, 'edit'])->name('talks.edit');
    Route::put('/talks/{talk}', [TalkProposalController::class, 'update'])->name('talks.update');
});

// Reviewer dashboard and review submission
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');
});
