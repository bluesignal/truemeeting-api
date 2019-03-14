<?php
/**
 * TrueMeeting API
 * TrueMeetingClientTest.php
 *
 * @author    Marc Heuker of Hoek
 * @copyright 2019, BlueSignal
 * @license   MIT
 */

namespace BlueSignal\TrueMeetingApi;

use BlueSignal\TrueMeetingApi\Exception\TrueMeetingException;
use BlueSignal\TrueMeetingApi\Exception\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class TrueMeetingClientTest extends TestCase
{

    /**
     * @throws TrueMeetingException
     */
    public function testCreateRoom()
    {
        $mock = new MockHandler([
            new Response(200, [], '{
                "uuid":"d6cebb7fa1708100ef651889061cdde4",
                "name":"Demo2","welcome_message":"Welcome to this room!",
                "max_participants":2,"private":true,"server":null
            }'),
        ]);

        $client = $this->getInstance($mock);

        $room = $client->createRoom('demo', 'Welcome to the demo room');
        self::assertEquals('d6cebb7fa1708100ef651889061cdde4', $room->getUuid());
        self::assertEquals('Demo2', $room->getName());
        self::assertEquals('Welcome to this room!', $room->getWelcomeMessage());
        self::assertEquals(2, $room->getMaxParticipants());
        self::assertTrue($room->isPrivate());
    }

    /**
     * @throws TrueMeetingException
     */
    public function testUnauthorized()
    {
        $mock = new MockHandler([
            new Response(401, [], '{
                "message":"Unauthorized"
            }'),
        ]);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized');

        $client = $this->getInstance($mock);
        $client->createRoom('demo', 'Welcome to the demo room');
    }

    private function getInstance(MockHandler $mock, $token = 'abc123'): TrueMeetingClient
    {
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        return new TrueMeetingClient($token, 'localhost', $client);
    }
}
