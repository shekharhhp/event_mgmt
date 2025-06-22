<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TalkProposal;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class StatisticsApiController extends Controller
{
    public function overview()
    {
        $totalProposals = TalkProposal::count();
        $averageRating = Review::avg('rating');

        // Count proposals per tag
        $proposalsPerTag = Tag::withCount('talkProposals')->get()->map(function ($tag) {
            return [
                'tag' => $tag->name,
                'count' => $tag->talk_proposals_count,
            ];
        });

        return response()->json([
            'total_proposals' => $totalProposals,
            'average_rating' => round($averageRating, 2),
            'proposals_per_tag' => $proposalsPerTag,
        ]);
    }
}
