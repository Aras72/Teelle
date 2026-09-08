<?php

declare(strict_types=1);

namespace App\Content;

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
}
