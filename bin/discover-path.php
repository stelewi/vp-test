<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Api\ApiClient;
use \App\Map\MapReader;
use App\PathFinder\PathFinder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$logger = new \Monolog\Logger('default_logger');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

$apiClient = new ApiClient($_ENV['API_ENDPOINT'], new GuzzleHttp\Client(), new MapReader());
$apiClient->setLogger($logger);

$pathFinder = new PathFinder($apiClient, new \App\PathFinder\CrudePathSuggester(), $_ENV['DEFAULT_DROID_SENDER_NAME']);

$logger->info("Sending droids to find a path....");
$path = $pathFinder->findPath()->asString();

$logger->info("Path found!");
$logger->info("");
$logger->info($path);

