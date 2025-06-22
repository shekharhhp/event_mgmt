<?php

namespace Database\Factories;

use App\Models\TalkProposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalkProposalFactory extends Factory
{
    protected $model = TalkProposal::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'pending',
            'speaker_id' => User::factory(),
        ];
    }
}
