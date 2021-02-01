<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Data\Move;
use App\Api\ApiClient;
use App\Data\Path;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiClient = new ApiClient($_ENV['API_ENDPOINT']);

$path = new Path([Move::forward(), Move::left(), Move::forward(), Move::right()]);

$response = $apiClient->sendDroid($path, $_ENV['DEFAULT_DROID_SENDER_NAME']);

var_dump($response);