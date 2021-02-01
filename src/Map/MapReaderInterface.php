<?php


namespace App\Map;


use App\Data\Map;

interface MapReaderInterface
{
    public function readMapString(string $mapString) : Map;

}