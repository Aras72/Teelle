<?php

declare(strict_types=1);

namespace App\Content;

use App\Models\GameVersion;
use Illuminate\Support\Facades\DB;

final class GameMetadataPayload
{
    public function forVersion(GameVersion $version): array
    {
        $facts = DB::table('game_facts')->where('game_version_id', $version->id)->first();
        if (! $facts) {
            return $this->template();
        }
        $age = DB::table('game_age_ranges')->leftJoin('age_bands', 'age_bands.id', '=', 'game_age_ranges.age_band_id')
            ->where('game_version_id', $version->id)->first();
        $payload = collect((array) $facts)->except(['id', 'game_version_id', 'created_at', 'updated_at'])->all();
        $payload['alternatives'] = $this->decodeAlternatives($facts->alternatives ?? null);
        $payload += ['age_band' => $age->code, 'minimum_age_months' => $age->minimum_age_months,
            'maximum_age_months_exclusive' => $age->maximum_age_months_exclusive];
        $payload['situations'] = $this->slugs($version, 'game_situations', 'situations', 'situation_id');
        $payload['locations'] = $this->slugs($version, 'game_locations', 'locations', 'location_id');
        $payload['moods'] = $this->slugs($version, 'game_moods', 'moods', 'mood_id');
        $payload['tags'] = $this->slugs($version, 'game_tags', 'tags', 'tag_id');
        $payload['player_requirement'] = $this->slugs($version, 'game_player_requirements', 'player_requirements', 'player_requirement_id')[0] ?? null;
        $payload['materials'] = DB::table('game_materials')->join('materials', 'materials.id', '=', 'game_materials.material_id')
            ->where('game_version_id', $version->id)->get(['materials.slug', 'game_materials.requirement', 'game_materials.quantity_note'])->map(fn ($row) => (array) $row)->all();
        $payload['safety_flags'] = DB::table('game_safety_rules')->join('safety_rules', 'safety_rules.id', '=', 'game_safety_rules.safety_rule_id')
            ->where('game_version_id', $version->id)->pluck('safety_rules.code')->all();

        return $payload;
    }

    public function template(): array
    {
        return ['age_band' => '4-6y', 'minimum_age_months' => 48, 'maximum_age_months_exclusive' => 84,
            'duration_min_minutes' => 5, 'duration_max_minutes' => 15, 'prep_time_minutes' => 2,
            'space_required' => 'room', 'noise_level' => 'quiet', 'mess_level' => 'none',
            'minimum_children' => 1, 'maximum_children' => 2, 'minimum_adults' => 1, 'required_adult' => true,
            'child_energy' => 'medium', 'caregiver_energy' => 'low', 'interaction_type' => 'cooperative',
            'caregiver_involvement' => 'shared', 'setup_complexity' => 'simple',
            'source_title' => '', 'source_url' => 'https://', 'cultural_origin' => 'ایران',
            'content_priority' => 'normal', 'priority_reason' => null, 'alternatives' => [],
            'situations' => ['between-meals'], 'locations' => ['home-inside'], 'moods' => ['calm'],
            'tags' => ['cooperative'], 'player_requirement' => 'child-and-adult', 'materials' => [],
            'safety_flags' => ['sensory_intensity']];
    }

    private function decodeAlternatives(mixed $raw): array
    {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($raw) ? $raw : [];
    }

    private function slugs(GameVersion $version, string $pivot, string $taxonomy, string $foreignKey): array
    {
        return DB::table($pivot)->join($taxonomy, "$taxonomy.id", '=', "$pivot.$foreignKey")
            ->where("$pivot.game_version_id", $version->id)->pluck("$taxonomy.slug")->all();
    }
}
