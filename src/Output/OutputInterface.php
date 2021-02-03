<?php


namespace App\Output;


interface OutputInterface
{
    const STANDARD = 1;
    const VERBOSE = 2;
    const VERY_VERBOSE = 3;

    public function message(string $output, int $level) : void;

}