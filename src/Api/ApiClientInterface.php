<?php


namespace App\Api;


use App\Api\Data\ApiResponse;
use App\Data\Path;

interface ApiClientInterface
{
    public function sendDroid(Path $path, string $senderName) : ApiResponse;

}