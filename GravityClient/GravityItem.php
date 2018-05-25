<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;


/**
 * An item is something that can be recommended to users.
 */
class GravityItem
{
    public function __construct()
    {
        $this->fromDate = 0;
        $this->toDate = 2147483647;
        $this->nameValues = array();
    }

    /**
     *
     * The itemId is a unique identifier of the item.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    public $itemId;

    /**
     * The item title. This is a short, human-readable name of the item.
     * If the title is available in multiple languages, try to use your system's primary language, for example English.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    public $title;

    /**
     *
     * The type of the item. It can be used to create different item families.
     * The purpose of itemtype is to differentiate items which will have different namevalue properties.
     * Examples:
     * <ul>
     *    <li>Book</li>
     *    <li>Ticket</li>
     *    <li>Computer</li>
     *    <li>Food</li>
     * </ul>
     *
     * @var string
     */
    public $itemType;

    /**
     * The value of hidden. A hidden item will be never recommended.
     *
     * @var boolean
     */
    public $hidden;

    /**
     *
     * An item is never recommended before this date.
     * The date in unixtime format, the number of seconds elapsed since 1970.01.01 00:00:00 UTC, as returned by the standard time() PHP function.
     * Set it to 0 if not important.
     *
     * @var int
     */
    public $fromDate;

    /**
     *
     * An item is never recommended after this date.
     * The date in unixtime format, the number of seconds elapsed since 1970.01.01 00:00:00 UTC, as returned by the standard time() PHP function.
     * Set it to a big number (eg. 2147483647) if not important.
     *
     * @var int
     */
    public $toDate;


    /**
     *
     * The NameValues for the item.
     * <p>NameValues provide additional description of the item.</p>
     * <p>There can multiple NameValues with the same name.</p>
     * <p>The order of NameValues among different names will not be preserved, but the order of the values for the same name will be preserved.</p>
     * <p>The recommendation engine can be configured to use some properties to create a relation between items.</p>
     * <p>A possible list of names:</p>
     * <table border="1">
     *    <tr><th>Name</th><th>Localizable</th><th>Description</th></tr>
     *    <tr><td>Title</td><td>Yes</td><td>The title of the item.</td></tr>
     *    <tr><td>Description</td><td>Yes</td><td>The description of item.</td></tr>
     *    <tr><td>CategoryPath</td><td>No</td><td>The full path of the item's category.
     *        For example, CategoryPath might be "books/computers/databases" for a book about databases.
     *        CategoryPath can be repeated if an item belongs to multiple categories. Use "/" only for separating levels.
     *        Later it is possible to use the recommendation engine to filter the list of items based on category, so it can provide recommendations for "Computer Books" category or only for "Computer Books &gt; Database" category.
     *  </td></tr>
     *    <tr><td>Tags</td><td>No</td><td>Tags for the item. Specify a separate namevalue for each tag.</td></tr>
     *    <tr><td>State</td><td>No</td><td>The state of the item, for example 'available', 'out of stock' etc.</td></tr>
     *    <tr><td>Price</td><td>No</td><td>The price of the item.</td></tr>
     * </table>
     *
     * The recommendation engine can accept arbitrary namevalues, the list above is just an example of basic properties that are used almost everywhere.
     * The NameValues which are relevant to business rules and possible content based filtering should be provided to the recommendation engine.
     *
     * <p>You can use localization by appending a language identifier to the Name of the NameValue. For example, Title_EN, Title_HU.
     * It is possible to use both non-localized and localized version.
     * </p>
     *
     * @var GravityNameValue[]
     */
    public $nameValues;

}