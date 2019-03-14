<?php
/**
 * TrueMeeting API
 * InvalidException.php
 *
 * @author    Marc Heuker of Hoek
 * @copyright 2019, BlueSignal
 * @license   MIT
 */

namespace BlueSignal\TrueMeetingApi\Exception;

use Throwable;

class InvalidException extends TrueMeetingException
{
    /**
     * @var string[]
     */
    protected $errors = [];

    public function __construct($message = "", array $errors = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * Returns all the validation errors from the server
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
