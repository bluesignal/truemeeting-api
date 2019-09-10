<?php
/**
 * TrueMeeting API
 * Room.php
 *
 * @author    Marc Heuker of Hoek
 * @copyright 2019, BlueSignal
 * @license   MIT
 */

namespace BlueSignal\TrueMeetingApi\Entity;

use stdClass;

class Room
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
     * @var string
     */
    private $welcomeMessage;

    /**
     * @var int
     */
    private $maxParticipants;

    /**
     * @var bool
     */
    private $private;

    /**
     * @var string
     */
    private $url;

    /**
     * @param stdClass $object
     * @return Room
     */
    public static function fromObject(stdClass $object): Room
    {
        return (new Room())
            ->setUuid($object->uuid)
            ->setName($object->name)
            ->setWelcomeMessage($object->welcome_message)
            ->setMaxParticipants($object->max_participants)
            ->setPrivate($object->private)
            ->setUrl($object->url);
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
     * @return Room
     */
    public function setUuid(string $uuid): Room
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
     * @return Room
     */
    public function setName(string $name): Room
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getWelcomeMessage(): string
    {
        return $this->welcomeMessage;
    }

    /**
     * @param string $welcomeMessage
     * @return Room
     */
    public function setWelcomeMessage(string $welcomeMessage): Room
    {
        $this->welcomeMessage = $welcomeMessage;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    /**
     * @param int $maxParticipants
     * @return Room
     */
    public function setMaxParticipants(int $maxParticipants): Room
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     * @return Room
     */
    public function setPrivate(bool $private): Room
    {
        $this->private = $private;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Room
     */
    public function setUrl(string $url): Room
    {
        $this->url = $url;
        return $this;
    }
}
