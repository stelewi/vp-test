<?php


namespace App\Api\Data;


use App\Data\Coordinates;
use App\Data\Map;

class ApiResponse
{
    const DROID_STATE_GOAL_REACHED = 0;
    const DROID_STATE_CRASHED = 1;
    const DROID_STATE_GONE = 2;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var Map
     */
    private Map $map;

    /**
     * @var int
     */
    private int $droidState;

    /**
     * @var Coordinates|null
     */
    private ?Coordinates $crashPosition;

    /**
     * ApiResponse constructor.
     * @param string $message
     * @param Map $map
     * @param int $droidState
     * @param Coordinates|null $crashPosition
     */
    public function __construct(string $message, Map $map, int $droidState, ?Coordinates $crashPosition)
    {
        $this->message = $message;
        $this->map = $map;
        $this->droidState = $droidState;
        $this->crashPosition = $crashPosition;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return Map
     */
    public function getMap(): Map
    {
        return $this->map;
    }

    /**
     * @return int
     */
    public function getDroidState(): int
    {
        return $this->droidState;
    }

    /**
     * @return Coordinates|null
     */
    public function getCrashPosition(): ?Coordinates
    {
        return $this->crashPosition;
    }
}