<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Review;
use App\Models\User;
use App\Models\TalkProposal;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_belongs_to_reviewer()
    {
        $review = Review::factory()->create();
        $this->assertInstanceOf(User::class, $review->reviewer);
    }

    public function test_review_belongs_to_talk_proposal()
    {
        $review = Review::factory()->create();
        $this->assertInstanceOf(TalkProposal::class, $review->talkProposal);
    }
}