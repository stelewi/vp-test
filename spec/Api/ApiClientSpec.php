<?php

namespace spec\App\Api;

use App\Api\ApiClient;
use App\Api\Data\ApiResponse;
use App\Data\Coordinates;
use App\Data\Map;
use App\Data\Move;
use App\Data\Path;
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
        $path = Path::create([Move::forward(), Move::left(), Move::forward(), Move::right()]);

        $httpClient->request(
            'GET',
            'https://deathstar.dev-tests.vp-ops.com/alliance.php',
            [
                'query' => [
                    'name' => 'Chewbacca',
                    'path' => 'flfr'
                ],
                'http_errors' => false
            ]
        )->willReturn($httpResponse);

        $httpResponse->getStatusCode()->willReturn(417);
        $httpResponse->getBody()->willReturn(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'example-response-crashed.json')
        );

        $expectedMapString = "#   x   #\n### **###\n##   * ##";

        $mapReader->readMapString($expectedMapString)->willReturn($map);

        // Chewy is sending a droid on an ultimately fatal path,
        // but its sacrifice is not in vain!
        $apiResponse = $this->sendDroid($path, 'Chewbacca');

        $apiResponse->getMessage()->shouldBe('Crashed at position 3,5.');
        $apiResponse->getMap()->shouldBe($map);
        $apiResponse->getDroidState()->shouldBe(ApiResponse::DROID_STATE_CRASHED);
        $apiResponse->getCrashPosition()->shouldHaveType(Coordinates::class);
        $apiResponse->getCrashPosition()->getX()->shouldBe(3);
        $apiResponse->getCrashPosition()->getY()->shouldBe(5);
    }

    function it_sends_a_droid_on_a_path_no_crash_but_droid_did_not_succeed(
        ClientInterface $httpClient,
        ResponseInterface $httpResponse,
        MapReaderInterface $mapReader,
        Map $map)
    {
        $path = Path::create([Move::forward(), Move::left()]);

        $httpClient->request(
            'GET',
            'https://deathstar.dev-tests.vp-ops.com/alliance.php',
            [
                'query' => [
                    'name' => 'Chewbacca',
                    'path' => 'fl'
                ],
                'http_errors' => false
            ]
        )->willReturn($httpResponse);

        $httpResponse->getStatusCode()->willReturn(410);
        $httpResponse->getBody()->willReturn(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'example-response-lost.json')
        );

        $expectedMapString = "#   x   #\n### **###";

        $mapReader->readMapString($expectedMapString)->willReturn($map);

        // Chewy is sending a droid into the void
        $apiResponse = $this->sendDroid($path, 'Chewbacca');

        $apiResponse->getMessage()->shouldBe('Lost contact.');
        $apiResponse->getMap()->shouldBe($map);
        $apiResponse->getDroidState()->shouldBe(ApiResponse::DROID_STATE_GONE);
        $apiResponse->getCrashPosition()->shouldBeNull();
    }

    function it_sends_a_droid_on_a_path_that_succeeds(
        ClientInterface $httpClient,
        ResponseInterface $httpResponse,
        MapReaderInterface $mapReader,
        Map $map)
    {
        $path = Path::create([Move::forward(), Move::left(), Move::forward()]);

        $httpClient->request(
            'GET',
            'https://deathstar.dev-tests.vp-ops.com/alliance.php',
            [
                'query' => [
                    'name' => 'Chewbacca',
                    'path' => 'flf'
                ],
                'http_errors' => false
            ]
        )->willReturn($httpResponse);

        $httpResponse->getStatusCode()->willReturn(200);
        $httpResponse->getBody()->willReturn(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'example-response-success.json')
        );

        $expectedMapString = "#   x   #\n### **###\n##   * ##";

        $mapReader->readMapString($expectedMapString)->willReturn($map);

        // Chewy is sending a droid on a successful path,
        $apiResponse = $this->sendDroid($path, 'Chewbacca');

        $apiResponse->getMessage()->shouldBe('Great Success!');
        $apiResponse->getMap()->shouldBe($map);
        $apiResponse->getDroidState()->shouldBe(ApiResponse::DROID_STATE_GOAL_REACHED);
        $apiResponse->getCrashPosition()->shouldBeNull();
    }

}
