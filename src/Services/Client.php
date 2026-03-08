<?php

declare(strict_types=1);

namespace SvenAhrens\SbomAnalyzer\Services;

final class Client
{
    public function fetchCves(string $cpe): array
    {
        $url = $_ENV['API_URL'] . '?' . http_build_query(['cpeName' => $cpe]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200 || $response === false) {
            return [];
        }

        $data = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
        return $data['vulnerabilities'] ?? [];
    }

    public function rateLimit(): void
    {
        usleep((int) $_ENV['RATE_LIMIT']);
    }
}
