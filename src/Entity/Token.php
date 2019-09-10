<?php
/**
 * TrueMeeting API
 * Token.php
 *
 * @author    Marc Heuker of Hoek
 * @copyright 2019, BlueSignal
 * @license   MIT
 */

namespace BlueSignal\TrueMeetingApi\Entity;

use DateTime;
use stdClass;

class Token
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTime
     */
    private $expires;

    /**
     * @var string
     */
    private $token;

    public static function fromObject(stdClass $object): Token
    {
        return (new Token())
            ->setUuid($object->uuid)
            ->setName($object->name)
            ->setExpires(DateTime::createFromFormat(DateTime::ATOM, $object->expires))
            ->setToken($object->token);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Token
     */
    public function setUuid(string $uuid): Token
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Token
     */
    public function setName(string $name): Token
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpires(): DateTime
    {
        return $this->expires;
    }

    /**
     * @param DateTime $expires
     * @return Token
     */
    public function setExpires(DateTime $expires): Token
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Token
     */
    public function setToken(string $token): Token
    {
        $this->token = $token;
        return $this;
    }
}
