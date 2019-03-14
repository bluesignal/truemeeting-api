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

class Organization
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
     * @param stdClass $object
     * @return Organization
     */
    public static function fromObject(stdClass $object): Organization
    {
        return (new Organization())
            ->setUuid($object->uuid)
            ->setName($object->name);
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
     * @return Organization
     */
    public function setUuid(string $uuid): Organization
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
     * @return Organization
     */
    public function setName(string $name): Organization
    {
        $this->name = $name;
        return $this;
    }
}
