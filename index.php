<?php

require_once __DIR__ . '/vendor/autoload.php';

use SvenAhrens\SbomAnalyzer\Services\Analyzer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$analyzer = new Analyzer();
$analyzer->analyze(__DIR__ . $_ENV['FILE_URL']);
