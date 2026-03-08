<?php

declare(strict_types=1);

namespace SvenAhrens\SbomAnalyzer\Services;

final class Mapper
{
    public function map(array $rawCves): array
    {
        $vulnerabilities = [];

        foreach ($rawCves as $item) {
            $cve = $item['cve'];

            $vulnerabilities[] = [
                'id'          => $cve['id'],
                'description' => $this->extractDescription($cve),
                'published'   => substr($cve['published'] ?? '', 0, 10),
            ];
        }

        return $vulnerabilities;
    }

    private function extractDescription(array $cve): string
    {
        foreach ($cve['descriptions'] ?? [] as $description) {
            if ($description) return $description['value'];
        }
        return 'No description available.';
    }
}
