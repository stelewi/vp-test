<?php


namespace App\PathFinder;


use App\Api\Data\ApiResponse;

interface ProgressTracker
{
    public function updateProgress(ApiResponse $response) : void;
}