<?php

declare(strict_types=1);

namespace SvenAhrens\SbomAnalyzer\Services;

final class Analyzer
{
    public function __construct(
        private readonly Parser $parser = new Parser(),
        private readonly Client $client = new Client(),
        private readonly Mapper $mapper = new Mapper(),
        private readonly Report $report = new Report(),
    ) {}

    public function analyze(string $filePath): void
    {
        echo "Parsing SBOM file...\n";
        $components = $this->parser->parse($filePath);
        echo "Found " . count($components) . " components with CPE.\n";
        echo "Querying NVD API (rate limited — ~6s per request)...\n\n";

        $reports = [];

        foreach ($components as $i => $component) {
            echo sprintf("[%d/%d] Checking: %s %s\n", $i + 1, count($components), $component['name'], $component['version']);

            $vulnerabilities = $this->mapper->map(
                $this->client->fetchCves($component['cpe'])
            );

            $reports[] = array_merge($component, [
                'vulnerabilities' => $vulnerabilities,
            ]);

            if ($i < count($components) - 1) {
                $this->client->rateLimit();
            }
        }

        $this->report->render($reports, $filePath);
    }
}
