<?php

namespace Gravityrd\GravityClient\Exceptions;

class GravityRequestException extends \Exception
{
    /**
     * GravityRequestException constructor.
     *
     * @param array           $messages
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct( $message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }

}