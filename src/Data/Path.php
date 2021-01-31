<?php


namespace App\Data;


class Path
{
    /**
     * @var Move[]
     */
    private array $pathData;

    /**
     * Path constructor.
     * @param Move[] $pathData
     */
    public function __construct(array $pathData)
    {
        $this->pathData = $pathData;
    }

    public function asString() : string
    {
        return implode('', $this->pathData);
    }
}