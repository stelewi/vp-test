<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Data\Move;
use App\Api\ApiClient;
use App\Data\Path;
use \App\Map\MapReader;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$apiClient = new ApiClient($_ENV['API_ENDPOINT'], new GuzzleHttp\Client(), new MapReader());

$path = Path::create(array_fill(0, 10, Move::forward()));

$response = $apiClient->sendDroid($path, $_ENV['DEFAULT_DROID_SENDER_NAME']);

var_dump($response);