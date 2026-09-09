<?php

declare(strict_types=1);

namespace App\Content;

use App\Models\GameVersion;
use Illuminate\Support\Facades\DB;

final class ContentHash
{
    public function make(array $content): string
    {
        $canonical = [
            'title' => trim((string) ($content['title'] ?? '')),
            'summary' => trim((string) ($content['summary'] ?? '')),
            'instructions' => array_values($content['instructions'] ?? []),
            'safety_copy' => trim((string) ($content['safety_copy'] ?? '')),
            'contraindications' => array_values($content['contraindications'] ?? []),
            'supervision_level' => (string) ($content['supervision_level'] ?? ''),
        ];

        return hash('sha256', json_encode($canonical, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function reviewScope(GameVersion $version): string
    {
        $snapshot = [
            'content_hash' => $version->content_hash,
            'facts' => ($facts = DB::table('game_facts')->where('game_version_id', $version->id)->first()) === null
                ? null
                : collect((array) $facts)->except(['id', 'game_version_id', 'created_at', 'updated_at'])->all(),
        ];
        foreach ([
            'game_age_ranges', 'game_situations', 'game_locations', 'game_energy_levels', 'game_moods',
            'game_player_requirements', 'game_tags', 'game_materials', 'game_safety_rules', 'game_media',
        ] as $table) {
            $snapshot[$table] = DB::table($table)->where('game_version_id', $version->id)->orderBy('id')->get()
                ->map(fn (object $row): array => collect((array) $row)->except('id')->all())->all();
        }

        return hash('sha256', json_encode($snapshot, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
