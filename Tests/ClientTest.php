<?php
declare(strict_types=1);

namespace Gravityrd\GravityClientTest;

use Gravityrd\GravityClient\ClientConfiguration;
use Gravityrd\GravityClient\GravityClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\Psr7\copy_to_string;

/**
 * Class ClientTest
 * @package GravityTest
 */
class ClientTest extends TestCase
{

    public function testIfTrue()
    {
        $this->assertTrue(true);
    }

    public function testIfClientIsThere()
    {
        $client = new Client();
        $msgFactory = new GuzzleMessageFactory();
//        $msgFactory = MessageFactoryDiscovery::find();
        $client->setDefaultResponse($msgFactory->createResponse(200, null, [], "valami"));
        $resp = $client->sendRequest($msgFactory->createRequest("GET", "https://gravityrc.com"));
        $this->assertEquals(200, $resp->getStatusCode());
        $this->assertEquals("valami", copy_to_string($resp->getBody()));
    }

    private function assertThrows(string $exception, callable $fn, string $message = null, int $code = null)
    {
        if (!class_exists($exception)) {
            throw new \InvalidArgumentException("Class: [${exception}] could not be loaded!");
        }

        try {
            $fn();
        } catch (\Exception $e) {

            $this->assertEquals($exception, get_class($e));

            if ($message !== null) {
                $this->assertEquals($message, $e->getMessage());
            }

            if ($code !== null) {
                $this->assertEquals($code, $e->getCode());
            }
        }
    }

    public function testInstantiation()
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new GravityClient(
                new ClientConfiguration("mockUser", "mockPass", ""),
                new Client());
        }, "Invalid configuration. Remote URL must be specified.", GRAVITY_ERRORCODE_CONFIG_ERROR);

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new GravityClient(
                new ClientConfiguration("mockUser", "mockPass", "https://yusp.com", -2),
                new Client());
        }, "Invalid configuration. Timeout must be a positive integer.", GRAVITY_ERRORCODE_CONFIG_ERROR);
    }

}