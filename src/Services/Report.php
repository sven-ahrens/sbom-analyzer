<?php

declare(strict_types=1);

namespace SvenAhrens\SbomAnalyzer\Services;

final class Report
{
    public function render(array $reports, string $sbomFile): void
    {
        $vulnerable = array_filter($reports, fn($report) => !empty($report['vulnerabilities']));

        echo "\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║           SBOM VULNERABILITY ANALYSIS REPORT            ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";
        echo "  File    : $sbomFile\n";
        echo "  Scanned : " . date('Y-m-d H:i:s') . "\n";
        echo "  Components scanned   : " . count($reports) . "\n";
        echo "  Vulnerable components: " . count($vulnerable) . "\n";
        echo "──────────────────────────────────────────────────────────\n\n";

        foreach ($vulnerable as $r) {
            echo "┌─ {$r['name']} v{$r['version']}\n";
            echo "│  CPE      : {$r['cpe']}\n";
            echo "│  Licenses : " . (implode(', ', $r['licenses']) ?: 'N/A') . "\n";
            echo "│  CVEs     : " . count($r['vulnerabilities']) . "\n│\n";

            foreach ($r['vulnerabilities'] as $v) {
                $desc = wordwrap($v['description'], 70, "\n│     ", true);
                echo "│  {$v['id']}\n";
                echo "│  Published : {$v['published']}\n";
                echo "│  Desc      : $desc\n│\n";
            }

            echo "└──────────────────────────────────────────────────────────\n\n";
        }

        $this->renderSummary($vulnerable);
    }

    private function renderSummary(array $reports): void
    {
        echo "SUMMARY\n";
        echo str_pad('Component', 22) . str_pad('Version', 12) . "CVEs Found\n";
        echo str_repeat('─', 45) . "\n";

        foreach ($reports as $r) {
            echo str_pad($r['name'], 22)
                . str_pad($r['version'], 12)
                . count($r['vulnerabilities']) . "\n";
        }
    }
}
