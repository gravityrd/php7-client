<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;

/**
 * A name and a value. This can be used to provide information about items, users and events.
 */
class GravityNameValue
{
    /**
     *
     * The name.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    public $name;

    /**
     *
     * The value.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    public $value;

    /**
     * Creates a new instance of a namevalue pair.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @param string <var>$name</var> The name.
     * @param string <var>$value</var> The value.
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}