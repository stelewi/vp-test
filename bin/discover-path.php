<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Api\ApiClient;
use App\Map\MapReader;
use App\PathFinder\PathFinder;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$apiClient = new ApiClient($_ENV['API_ENDPOINT'], new GuzzleHttp\Client(), new MapReader());
$pathFinder = new PathFinder($apiClient, new \App\PathFinder\CrudePathSuggester(), $_ENV['DEFAULT_DROID_SENDER_NAME']);

echo "sending droids, please wait...\n";

$path = $pathFinder->findPath()->asString();

echo "many droids died to bring us this information: \n\n";
echo $path . "\n\n";