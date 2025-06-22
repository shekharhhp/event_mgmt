<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\TalkProposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'talk_proposal_id' => TalkProposal::factory(),
            'reviewer_id' => User::factory(),
            'comments' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
