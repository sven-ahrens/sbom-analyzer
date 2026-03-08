<?php

declare(strict_types=1);

namespace SvenAhrens\SbomAnalyzer\Services;

final class Parser
{
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: $filePath");
        }

        $json = json_decode(file_get_contents($filePath), true, flags: JSON_THROW_ON_ERROR);
        $components = [];

        foreach ($json['components'] ?? [] as $component) {
            if (empty($component['cpe'])) continue;

            $components[] = [
                'name' => $component['name'],
                'version' => $component['version'],
                'cpe' => $component['cpe'],
                'licenses' => array_map(fn($license) => $license['license']['id'] ?? 'Unknown', $component['licenses'] ?? []),
            ];
        }

        return $components;
    }
}
