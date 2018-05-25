<?php
namespace Gravityrd\GravityClient\Exceptions;

use Gravityrd\GravityClient\Exceptions;

/**
 * Class ClientConfigurationValidationException
 * @package Gravityrd\GravityClient\Exceptions
 */
class ClientConfigurationValidationException extends Exceptions\ClientConfigurationException
{
    /**
     * ClientConfigurationException constructor.
     * @param array $messages
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $messages, $code = 0, \Throwable $previous = null) {
        $message = "";

        foreach ($messages as $prop => $message){
            $message .= "[${prop}]: ${message} \r\n";
        }

        parent::__construct($message,$code,$previous);
    }
}