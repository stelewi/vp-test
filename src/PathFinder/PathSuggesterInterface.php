<?php


namespace App\PathFinder;


use App\Data\Map;
use App\Data\Path;

interface PathSuggesterInterface
{
    public function initialSuggestion() : Path;
    public function informedSuggestion(Map $map) : Path;
}