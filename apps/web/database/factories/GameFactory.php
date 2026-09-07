<?php

namespace Database\Factories;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Game> */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'status' => GameStatus::Draft,
            'current_published_version_id' => null,
        ];
    }
}
