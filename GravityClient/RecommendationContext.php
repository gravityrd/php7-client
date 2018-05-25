<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;


/**
 * Contains information for a recommendation request.
 */
class RecommendationContext
{
    function __construct()
    {
        $this->recommendationTime = time();
    }

    /**
     *
     * The time of the recommendation (seconds in unixtime format), the time when it will be shown to the end user.
     * Use a value as returned by the standard time() PHP function.
     *
     * @var int
     */
    public $recommendationTime;

    /**
     *
     * The value of the maximum number of items in the result.
     * The maximum number of the items in the result can be also limited by the configuration of the scenario.
     * If set to 0, this number of items is determined by the scenario.
     *
     * @var int
     */
    public $numberLimit;

    /**
     *
     * The value of scenarioId. Scenarios are defined by the scenario management API.
     * A scenario describes a way how recommended items will be filtered, ordered.
     *
     * @var string
     */
    public $scenarioId;

    /**
     *
     * The NameValues for the context.
     * NameValues can describe the parameters for the actual scenario, like current item id, filtering by category etc.
     * Item-to-item recommendation is possible by a specific scenario which parses a NameValue describing the current item,
     * or multiple NameValues if there are multiple actual items.
     * The list of allowed names depends on the actual scenario.
     * <p>The scenario can also specify that the result is not a list of items, but a list of values of item NameValues.</p>
     * <table border="1">
     *    <tr><th>Name</th><th>Description</th></tr>
     *    <tr><td>CurrentItemId</td><td>The identifier of the actual item, if the current page is an item page.</td></tr>
     *    <tr><td>ItemOnPage</td><td>Identifier of item displayed elsewhere on the page. They will be excluded from recommendation. This namevalue can be used multiple times to provide a list of items.</td></tr>
     *    <tr><td>CartItemId</td><td>Identifier of item in the current shopping cart. This can provide additional information to improve the quality of recommendation. This namevalue must be used as many times as many items the shopping cart contains.</td></tr>
     *    <tr><td>CartItemQuantity</td><td>The quantity of items in the current shopping cart, in the same order as CartItemId namevalues.</td></tr>
     *    <tr><td>Filter.*</td><td>If specified, only items having the specified name and value as metadata will be in the result.
     *            For example, the namevalue with name='Filter'.'CategoryId' and value='A' means that only items belonging to category 'A' will be in the result.</td></tr>
     *
     * </table>
     *
     * @var GravityNameValue[]
     */
    public $nameValues;

    /**
     * If not null, specifies which NameValues of the recommended items should be included in the result.
     * If null, the returned NameValues are determined by the actual scenario.
     *
     * @var GravityNameValue[]
     */
    public $resultNameValues;

}