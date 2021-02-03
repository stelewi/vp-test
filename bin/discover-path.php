<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Api\ApiClient;
use App\Map\MapReader;
use App\PathFinder\PathFinder;
use App\PathFinder\CrudePathSuggester;
use App\Output\Output;
use App\Output\OutputInterface;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$droidSenderName = $_ENV['DEFAULT_DROID_SENDER_NAME'];
$outputLogLevel = OutputInterface::STANDARD;

if(count($argv) > 1)
{
    // assuming 1st arg is a name if it's not a switch
    if(!preg_match('/^\-/', $argv[1]))
    {
        $droidSenderName = $argv[1];
    }

    // check for switches...
    foreach ($argv as $i => $arg)
    {
        switch ($arg)
        {
            case '-v':
                $outputLogLevel = OutputInterface::VERBOSE;
                break;

            case '-vv':
                $outputLogLevel = OutputInterface::VERY_VERBOSE;
                break;
        }
    }
}

$output = new Output($outputLogLevel);
$apiClient = new ApiClient($_ENV['API_ENDPOINT'], new GuzzleHttp\Client(), new MapReader());
$pathFinder = new PathFinder($apiClient, new CrudePathSuggester());

$output->message(strtolower($droidSenderName) . " is sending droids, please wait...\n\n", OutputInterface::VERBOSE);

try {
    $path = $pathFinder->findPath($droidSenderName, $output)->asString();

    $output->message("\n\nmany droids died to bring us this information: \n\n", OutputInterface::VERBOSE);
    $output->message($path . "\n\n", OutputInterface::STANDARD);
    return 0;
}
catch (\Exception $e)
{
    fwrite(STDERR, "Failed with exception: {$e->getMessage()}");
    fwrite(STDERR, $e->getTraceAsString());
    return 1;
}





