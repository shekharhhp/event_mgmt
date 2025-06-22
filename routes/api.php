<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Api\ReviewerApiController;
use App\Http\Controllers\Api\StatisticsApiController;

Route::get('/reviewers', [ReviewerApiController::class, 'index']); // Get all reviewers
Route::get('/talk-proposals/{id}/reviews', [ReviewerApiController::class, 'showReviews']); // Reviews for a proposal

Route::get('/statistics/talk-proposals', [StatisticsApiController::class, 'overview']); // Proposal stats

