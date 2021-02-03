<?php


namespace App\Output;


use App\Api\Data\ApiResponse;
use App\PathFinder\ProgressTracker;

class Output implements OutputInterface, ProgressTracker
{
    private int $level;
    private ?int $currentMapRow = 0;

    /**
     * Output constructor.
     * @param int $level
     */
    public function __construct(int $level)
    {
        $this->level = $level;
    }

    /**
     * send message to STDOUT
     * @param string $output
     * @param int $level
     */
    public function message(string $output, int $level): void
    {
        if($this->level < $level)
            return;

        echo $output;
    }

    /**
     * send map to STDOUT
     * @param ApiResponse $response
     */
    public function updateProgress(ApiResponse $response): void
    {
        if($this->level < OutputInterface::VERY_VERBOSE)
            return;

        $mapRowsAvailable = $response->getMap()->getRows();

        for($row = $this->currentMapRow; $row < $mapRowsAvailable; $row++)
        {
            echo $response->getMap()->getRowAsString($row) . "\n";
        }

        $this->currentMapRow = $mapRowsAvailable;
    }
}