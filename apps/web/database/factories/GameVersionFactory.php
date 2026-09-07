<?php

namespace Database\Factories;

use App\Enums\GameVersionStatus;
use App\Models\Game;
use App\Models\GameVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GameVersion> */
class GameVersionFactory extends Factory
{
    protected $model = GameVersion::class;

    public function definition(): array
    {
        $instructions = [fake()->sentence(), fake()->sentence()];
        $title = 'بازی آزمایشی '.fake()->unique()->numberBetween(1000, 999999);

        return [
            'game_id' => Game::factory(),
            'version_no' => 1,
            'status' => GameVersionStatus::Draft,
            'title' => $title,
            'summary' => fake()->sentence(),
            'instructions' => $instructions,
            'safety_copy' => 'این محتوای ساختگی فقط برای آزمون است',
            'contraindications' => [],
            'supervision_level' => 'same_room',
            'content_hash' => hash('sha256', $title.json_encode($instructions)),
            'review_quality' => 0,
            'approved_at' => null,
        ];
    }
}
