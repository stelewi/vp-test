<?php


namespace App\Api\Data;


use App\Data\Map;

class ApiResponse
{
    /**
     * @var string
     */
    private string $message;

    /**
     * @var Map
     */
    private Map $map;

    /**
     * ApiResponse constructor.
     * @param string $message
     * @param Map $map
     */
    private function __construct(string $message, Map $map)
    {
        $this->message = $message;
        $this->map = $map;
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


}