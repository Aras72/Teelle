<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StructuredMetadataRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $decoded = $this->filled('metadata_json') ? json_decode((string) $this->input('metadata_json'), true) : null;
        if (is_array($decoded) && ! $this->has('metadata')) {
            $this->merge(['metadata' => $decoded]);
        }
        if (is_array($this->input('metadata'))) {
            $metadata = $this->input('metadata');
            $metadata['required_adult'] = filter_var($metadata['required_adult'] ?? false, FILTER_VALIDATE_BOOLEAN);
            foreach (['situations', 'locations', 'moods', 'tags', 'safety_flags'] as $field) {
                $metadata[$field] = array_values(array_filter(array_unique(array_map('strval', is_array($metadata[$field] ?? null) ? $metadata[$field] : []))));
            }
            $metadata['materials'] = array_values(array_filter(array_map(function ($material): ?array {
                if (! is_array($material) || blank($material['slug'] ?? null)) {
                    return null;
                }

                return [
                    'slug' => (string) $material['slug'],
                    'requirement' => (string) ($material['requirement'] ?? 'required'),
                    'quantity_note' => filled($material['quantity_note'] ?? null) ? trim((string) $material['quantity_note']) : null,
                ];
            }, is_array($metadata['materials'] ?? null) ? $metadata['materials'] : [])));
            $this->merge(['metadata' => $metadata]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->can('content.edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'metadata_json' => ['nullable', 'json', 'max:50000'], 'metadata' => ['required', 'array'],
            'metadata.age_band' => ['required', 'exists:age_bands,code'],
            'metadata.minimum_age_months' => ['required', 'integer', 'min:6', 'max:155'],
            'metadata.maximum_age_months_exclusive' => ['required', 'integer', 'gt:metadata.minimum_age_months', 'max:156'],
            'metadata.duration_min_minutes' => ['required', 'integer', 'min:1', 'max:240'],
            'metadata.duration_max_minutes' => ['required', 'integer', 'gte:metadata.duration_min_minutes', 'max:360'],
            'metadata.prep_time_minutes' => ['required', 'integer', 'min:0', 'max:120'],
            'metadata.space_required' => ['required', 'in:lap,small,room,large,outdoor'],
            'metadata.noise_level' => ['required', 'in:quiet,moderate,loud'], 'metadata.mess_level' => ['required', 'in:none,light,messy'],
            'metadata.minimum_children' => ['required', 'integer', 'min:1', 'max:20'],
            'metadata.maximum_children' => ['required', 'integer', 'gte:metadata.minimum_children', 'max:30'],
            'metadata.minimum_adults' => ['required', 'integer', 'min:0', 'max:5'], 'metadata.required_adult' => ['required', 'boolean'],
            'metadata.child_energy' => ['required', 'exists:energy_levels,slug'], 'metadata.caregiver_energy' => ['required', 'exists:energy_levels,slug'],
            'metadata.interaction_type' => ['required', 'in:side_by_side,cooperative,competitive,pretend,conversation'],
            'metadata.caregiver_involvement' => ['required', 'in:active,shared,light'], 'metadata.setup_complexity' => ['required', 'in:none,simple,moderate'],
            'metadata.source_title' => ['required', 'string', 'max:255'], 'metadata.source_url' => ['required', 'url:http,https', 'max:2048'],
            'metadata.cultural_origin' => ['required', 'string', 'max:120'],
            'metadata.situations' => ['required', 'array', 'min:1'], 'metadata.situations.*' => ['required', 'distinct', Rule::exists('situations', 'slug')->where('is_active', true)],
            'metadata.locations' => ['required', 'array', 'min:1'], 'metadata.locations.*' => ['required', 'distinct', 'exists:locations,slug'],
            'metadata.moods' => ['required', 'array', 'min:1'], 'metadata.moods.*' => ['required', 'distinct', 'exists:moods,slug'],
            'metadata.tags' => ['required', 'array', 'min:1'], 'metadata.tags.*' => ['required', 'distinct', 'exists:tags,slug'],
            'metadata.player_requirement' => ['required', 'exists:player_requirements,slug'],
            'metadata.materials' => ['present', 'array', 'max:12'], 'metadata.materials.*.slug' => ['required', 'distinct', 'exists:materials,slug'],
            'metadata.materials.*.requirement' => ['required', 'in:required,optional'], 'metadata.materials.*.quantity_note' => ['nullable', 'string', 'max:255'],
            'metadata.safety_flags' => ['required', 'array', 'min:1'], 'metadata.safety_flags.*' => ['required', 'distinct', 'exists:safety_rules,code'],
        ];
    }
}
