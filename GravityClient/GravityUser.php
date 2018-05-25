<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;


/**
 * A user in the recommendation system. A user is an entity which generates event, and can get recommendations.
 */
class GravityUser
{
    function __construct()
    {
        $this->nameValues = array();
    }

    /**
     *
     * This is a unqiue identifier for a registered user.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    public $userId;

    /**
     *
     * NameValues provide additional description of the user.
     * There can multiple NameValues with the same name.
     * The order of NameValues will not be preserved.
     *
     * The recommendation engine in most cases does not require detailed information about the users, usually only some basic information can be used to enhance the quality of recommendation.
     * For example:
     * <table border="1">
     *    <tr><th>Name</th><th>Description</th></tr>
     *    <tr><td>ZipCode</td><td>The zip code of the user.</td></tr>
     *    <tr><td>City</td><td>The city of the user.</td></tr>
     *    <tr><td>Country</td><td>The country of the user.</td></tr>
     * </table>
     *
     * @var GravityNameValue[]
     */
    public $nameValues;

    /**
     *
     * True if the user is hidden.  A no more existing user should be set to hidden.
     *
     * @var boolean
     */
    public $hidden;
}