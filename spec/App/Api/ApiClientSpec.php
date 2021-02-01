<?php

namespace spec\App\Api;

use App\Api\ApiClient;
use App\Api\Data\ApiResponse;
use App\Data\Coordinates;
use App\Data\Map;
use App\Data\Move;
use App\Map\MapReaderInterface;
use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

class ApiClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApiClient::class);
    }

    function let(ClientInterface $httpClient, MapReaderInterface $mapReader)
    {
        $this->beConstructedWith(
            'https://deathstar.dev-tests.vp-ops.com/alliance.php',
            $httpClient,
            $mapReader
        );
    }

    function it_sends_a_droid_on_a_path_resulting_in_a_crash(
        ClientInterface $httpClient,
        ResponseInterface $httpResponse,
        MapReaderInterface $mapReader,
        Map $map)
    {
        $path = new Path([Move::forward(), Move::left(), Move::forward(), Move::right()]);

        $httpClient->request(
            'GET',
            'https://deathstar.dev-tests.vp-ops.com/alliance.php',
            [
                'query' => [
                    'name' => 'Chewbacca',
                    'path' => 'flfr'
                ]
            ]
        )->willReturn($httpResponse);

        $httpResponse->getStatusCode()->willReturn(417);
        $httpResponse->getBody()->willReturn(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'example-response-crashed.json')
        );

        $expectedMapString = "#   x   #
### **###
##   * ##";

        $mapReader->readMapString($expectedMapString)->willReturn($map);


        $apiResponse = $this->sendDroid($path, 'Chewbacca');

        $apiResponse->getMessage()->shouldBe('Crashed at position 3,5.');
        $apiResponse->getMap()->shouldBe($map);
        $apiResponse->getDroidState()->shouldBe(ApiResponse::DROID_STATE_CRASHED);
        $apiResponse->getCrashPosition()->shouldHaveType(Coordinates::class);
        $apiResponse->getCrashPosition()->getX()->shouldBe(3);
        $apiResponse->getCrashPosition()->getX()->shouldBe(5);




    }
}
