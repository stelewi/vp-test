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
    private function __construct(array $pathData)
    {
        $this->pathData = $pathData;
    }

    public function asString() : string
    {
        return implode('', $this->pathData);
    }

    public static function create(array $pathData) : self
    {
        return new self($pathData);
    }
}