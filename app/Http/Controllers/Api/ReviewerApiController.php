<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use App\Models\TalkProposal;

class ReviewerApiController extends Controller
{
    // Get all reviewers
    public function index()
    {
        $reviewers = User::whereHas('roles', function ($q) {
            $q->where('name', 'Reviewer');
        })->get(['id', 'name', 'email']);

        return response()->json($reviewers);
    }

    // Get all reviews for a specific talk proposal
    public function showReviews($id)
    {
        $reviews = Review::where('talk_proposal_id', $id)
            ->with('reviewer:id,name,email')
            ->get(['id', 'reviewer_id', 'comments', 'rating', 'created_at']);

        return response()->json($reviews);
    }
}
