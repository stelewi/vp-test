<?php

namespace spec\App\PathFinder;

use App\Data\Map;
use App\Data\Move;
use App\Data\Path;
use App\Map\MapReader;
use App\PathFinder\CrudePathSuggester;
use PhpSpec\ObjectBehavior;

class CrudePathSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CrudePathSuggester::class);
    }

    function it_sends_drone_forward_initially()
    {
        $path = $this->initialSuggestion();

        $path->shouldHaveType(Path::class);
        $path->getMoves()->shouldHaveCount(20);
        $path->getMoves()[0]->shouldBeLike(Move::forward());
        $path->getMoves()[1]->shouldBeLike(Move::forward());
        $path->getMoves()[2]->shouldBeLike(Move::forward());
        // etc...
    }

    function it_suggests_a_good_path_given_a_map()
    {
        $mapString =
            "#   x   #" . "\n" .
            "### * ###" . "\n" .
            "##  *  ##" . "\n" .
            "### #####";

        $map = (new MapReader())->readMapString($mapString);

        $suggestedPath = $this->informedSuggestion($map);

        $suggestedPath->shouldHaveType(Path::class);
        $suggestedPath->getMoves()->shouldBeArray();
        $suggestedPath->getMoves()[0]->shouldBeLike(Move::forward());
        $suggestedPath->getMoves()[1]->shouldBeLike(Move::forward());
        $suggestedPath->getMoves()[2]->shouldBeLike(Move::left());
        $suggestedPath->getMoves()[3]->shouldBeLike(Move::forward());
    }
}
