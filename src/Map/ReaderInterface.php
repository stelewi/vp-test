<?php


namespace App\Map;


use App\Data\Map;

interface ReaderInterface
{
    public function readMapString(string $mapString) : Map;

}