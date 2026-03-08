# SBOM Analyzer
Parses CycloneDX SBOM files and queries the NVD API to generate a vulnerability report.

## Getting Started

1. Clone the repository:
```bash
git clone https://github.com/sven-ahrens/sbom-analyzer
cd sbom-analyzer
```

2. Start the Docker container:
```bash
docker-compose up -d
```

3. Enter the container:
```bash
docker-compose exec php-fpm bash
```

4. Place your CycloneDX SBOM file (`.json`) into the `public/` directory

5. Update the file path in `.env` and run:
```bash
php index.php
```