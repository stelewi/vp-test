<?php

namespace spec\App\Map;

use App\Data\Coordinates;
use App\Data\Map;
use App\Map\MapReader;
use PhpSpec\ObjectBehavior;

class MapReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MapReader::class);
    }

    function it_converts_a_map_string_to_map_data()
    {
        $mapString =
            "#   x   #" . "\n" .
            "### **###" . "\n" .
            "##   * ##" . "\n" .
            "###  ####";

        $map = $this->readMapString($mapString);

        $map->shouldHaveType(Map::class);
        $map->getRows()->shouldBe(4);
        $map->getSquare(Coordinates::create(0,4))->shouldBe(Map::MAP_SQUARE_START_POS);
        $map->getSquare(Coordinates::create(0,0))->shouldBe(Map::MAP_SQUARE_OBSTACLE);
        $map->getSquare(Coordinates::create(1,3))->shouldBe(Map::MAP_SQUARE_EMPTY);
        $map->getSquare(Coordinates::create(2,5))->shouldBe(Map::MAP_SQUARE_DROID_PATH);
        $map->getSquare(Coordinates::create(3,5))->shouldBe(Map::MAP_SQUARE_OBSTACLE);
        $map->getSquare(Coordinates::create(4,5))->shouldBe(null);
        $map->getSquare(Coordinates::create(3,9))->shouldBe(null);
    }
}
