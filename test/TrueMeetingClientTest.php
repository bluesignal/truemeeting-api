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

use BlueSignal\TrueMeetingApi\Entity\User;
use BlueSignal\TrueMeetingApi\Exception\TrueMeetingException;
use BlueSignal\TrueMeetingApi\Exception\UnauthorizedException;
use DateTime;
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
                "max_participants":2,"private":true,"server":null,
                "url":"https://truemeeting.dev/#d6cebb7fa1708100ef651889061cdde4"
            }'),
        ]);

        $client = $this->getInstance($mock);

        $room = $client->createRoom('demo', 'Welcome to the demo room');
        self::assertEquals('d6cebb7fa1708100ef651889061cdde4', $room->getUuid());
        self::assertEquals('Demo2', $room->getName());
        self::assertEquals('Welcome to this room!', $room->getWelcomeMessage());
        self::assertEquals(2, $room->getMaxParticipants());
        self::assertEquals('https://truemeeting.dev/#d6cebb7fa1708100ef651889061cdde4', $room->getUrl());
        self::assertTrue($room->isPrivate());
    }

    /**
     * @throws TrueMeetingException
     * @throws UnauthorizedException
     */
    public function testCreateUser()
    {
        $mock = new MockHandler([
            new Response(200, [], '{
            "uuid":"2a1aa397fc6ab9f72882e1ce278929da",
            "display_name":"John Doe",
            "email":"j.doe@example.com",
            "first_name":"John",
            "last_name":"Doe",
            "active":true
            }'),
        ]);

        $client = $this->getInstance($mock);

        $user = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('j.doe@example.com');

        $client->createUser($user);

        self::assertEquals('2a1aa397fc6ab9f72882e1ce278929da', $user->getUuid());
        self::assertEquals('John Doe', $user->getDisplayName());
        self::assertEquals('j.doe@example.com', $user->getEmail());
        self::assertEquals('John', $user->getFirstName());
        self::assertEquals('Doe', $user->getLastName());
        self::assertTrue($user->isActive());
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

    /**
     * @throws TrueMeetingException
     * @throws UnauthorizedException
     */
    public function testGenerateToken()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'uuid' => '1234abc',
                'name' => 'generic',
                'expires' => '2000-01-01T12:00:00+01:00',
                'token' => '0123456789abcdef',
                'url' => 'https://truemeeting.dev/#1234abc',
            ])),
        ]);

        $client = $this->getInstance($mock);
        $token = $client->generateToken('room', 'abc1234');
        $expires = DateTime::createFromFormat(DateTime::ATOM, '2000-01-01T12:00:00+01:00');

        self::assertEquals('1234abc', $token->getUuid());
        self::assertEquals('generic', $token->getName());
        self::assertEquals('0123456789abcdef', $token->getToken());
        self::assertEquals($expires, $token->getExpires());
    }


    private function getInstance(MockHandler $mock, $token = 'abc123'): TrueMeetingClient
    {
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        return new TrueMeetingClient($token, 'localhost', $client);
    }
}
