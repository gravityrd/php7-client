<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;

use Gravityrd\GravityClient\Exceptions\ClientConfigurationValidationException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\RetryPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;


/**
 * Class GravityClient
 */
class GravityClient
{
    const VERSION = "2.0.0-beta";

    /**
     * The Http client that will perform the requests and return the PSR-7 compliant responses
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientConfiguration
     */
    protected $config;

    /**
     * GravityClient constructor.
     * @param ClientConfiguration $config
     * @param HttpClient $httpClient
     * @param MessageFactory|null $messageFactory
     * @throws \Exception
     * @throws ClientConfigurationValidationException
     * @throws NotFoundException
     */
    public function __construct(
        ClientConfiguration $config,
        HttpClient $httpClient = null,
        MessageFactory $messageFactory = null
    ) {
        $config->validateOrFail();

        try {
            $this->client = $httpClient ?? HttpClientDiscovery::find();
            $this->messageFactory = $messageFactory ?? MessageFactoryDiscovery::find();
            $this->config = $config;

        } catch (\Exception $ex) {
            throw new \Exception(
                "Unexpected exception while client initialization, see internal exception for further info.",
                $ex);
        }
    }

    /**
     * Adds an event to the recommendation engine.
     *
     * @param GravityEvent $event The event to add.
     * @param boolean $async true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addEvent(GravityEvent $event, $async): Response
    {
        return $this->addEvents([$event], $async);
    }

    /**
     * Adds multiple events to the recommendation engine.
     *
     * @param GravityEvent[] <var>$events</var> The events to add.
     * @param boolean <var>$async</var> true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addEvents(array $events, $async): Response
    {
        return $this->sendRequest('addEvents', "POST", ['async' => $async], $events);
    }

    /**
     * Adds an item to the recommendation engine.
     * If the item already exists with the specified itemId,
     * the entire item along with its NameValue pairs will be replaced to the new item specified here.
     *
     * @param GravityItem <var>$item</var> The item to add
     * @param boolean <var>$async</var> true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addItem(GravityItem $item, bool $async = true): Response
    {
        return $this->addItems(array($item), $async);
    }

    /**
     * Adds items to the recommendation engine.
     * If an item already exists with the specified itemId,
     * the entire item along with its NameValue pairs will be replaced to the new item specified here.
     *
     * @param GravityItem[] <var>$items</var> The items to add
     * @param boolean <var>$async</var> true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addItems(array $items, bool $async = true): Response
    {
        return $this->sendRequest('addItems', "POST", array('async' => $async), $items);
    }

    /**
     * Existing item will be updated. If item does not exist Exception will be thrown.
     * Update rules:
     *  - Key-value pairs won't be deleted only existing ones updated or new ones added. But If a key occurs in the key
     *    value list, then all values with the given key will be deleted and new values added in the recengine.
     *  - Hidden field has to be always specified!
     *
     * @param GravityItem $item The item to update
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function updateItem(GravityItem $item): Response
    {
        return $this->updateItems(array($item));
    }

    /**
     * Existing items will be updated. If item does not exist Exception will be thrown.
     * Update rules:
     *  - Key-value pairs won't be deleted only existing ones updated or new ones added. But If a key occurs in the key
     *    value list, then all values with the given key will be deleted and new values added in the recengine.
     *  - Hidden field has to be always specified!
     *
     * @param GravityItem[] $items The items to update
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function updateItems(array $items): Response
    {
        return $this->sendRequest('updateItems', "POST", [], $items);
    }


    /**
     * Adds user to the recommendation engine.
     * If the user already exists with the specified userId,
     * the entire user will be replaced with the new user specified here.
     *
     * @param GravityUser <var>$user</var> The user to add.
     * @param boolean <var>$async</var> true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addUser(GravityUser $user, bool $async = true): Response
    {
        return $this->addUsers(array($user), $async);
    }

    /**
     * Adds users to the recommendation engine. The existing users will be updated.
     * If a user already exists with the specified userId,
     * the entire user will be replaced with the new user specified here.
     *
     * @param GravityUser[] <var>$users</var> The users to add.
     * @param boolean <var>$async</var> true if the call is asynchronous. An asynchronous call
     * returns immediately after an input data checking,
     * a synchronous call returns only after the data is saved to database.
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function addUsers(array $users, bool $async = true): Response
    {
        return $this->sendRequest('addUsers', "POST", array('async' => $async), $users);
    }

    /**
     * Retrieves user metadata from the recommendation engine.
     *
     * @param string $userId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function getUserByUserId(string $userId): Response
    {
        return $this->sendRequest('getUser', "GET", ['userId' => $userId]);
    }

    /**
     * Retrieves user metadata from the recommendation engine if a user can be recognized from the specified cookieId.
     *
     * @param string $cookieId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function getUserByCookieId(string $cookieId): Response
    {
        return $this->sendRequest('getUser', 'GET', array('cookieId' => $cookieId));
    }

    /**
     * Retrieves full event history associated with the userId from the recommendation engine.
     * @param string $userId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function getEventsByUserId(string $userId): Response
    {
        return $this->sendRequest('getEvents', "GET", array('userId' => $userId));
    }

    /**
     * Retrieves full event history associated with the cookieId from the recommendation engine.
     * @param string $cookieId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function getEventsByCookieId(string $cookieId): Response
    {
        return $this->sendRequest('getEvents', 'GET', array('cookieId' => $cookieId));
    }

    /**
     * Deletes full event history assigned with the given cookieId from the recommendation engine.
     * @param $cookieId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function optOutCookie($cookieId): Response
    {
        return $this->sendRequest('optOut', 'GET', array('cookieId' => $cookieId), null);
    }

    /**
     * Deletes full event history and metadata assigned with the given userId from the recommendation engine.
     *
     * @param $userId
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function optOutUserId($userId): Response
    {
        return $this->sendRequest('optOut', 'GET', array('userId' => $userId), null);
    }

    /**
     * Returns a list of recommended items, based on the given context parameters.
     *
     * @param string <var>$userId</var> The identifier of the logged in user. If no user is logged in, null should be specified.
     * @param string <var>$cookieId</var> It should be a permanent identifier for the end users computer, preserving its value across browser sessions.
     * It should be always specified.
     * @param RecommendationContext <var>$context</var>
     *    Additional information which describes the actual scenario.
     * @return Response GravityItemRecommendation
     *    An object containing the recommended items and other information about the recommendation.
     * @throws \Http\Client\Exception
     */
    public function getItemRecommendation(
        string $userId,
        string $cookieId,
        RecommendationContext $context = null
    ): Response {
        return $this->sendRequest(
            'getItemRecommendation',
            "POST",
            [
                'userId' => $userId,
                'cookieId' => $cookieId,
            ],
            $context
        );
    }

    /**
     * Given the userId and the cookieId, we can request recommendations for multiple scenarios
     * (described by the context).
     * This function returns lists of recommended items for each of the given scenarios in an array.
     *
     * @param string <var>$userId</var> The identifier of the logged in user. If no user is logged in,
     * null should be specified.
     * @param string <var>$cookieId</var> It should be a permanent identifier for the end users computer,
     * preserving its value across browser sessions.
     * It should be always specified.
     * @param RecommendationContext[] <var>$context</var>
     * Additional Array of information which describes the actual scenarios.
     * @return Response GravityItemRecommendation[] An Array containing the recommended items for each scenario
     * with other information about the recommendation.
     * @throws \Http\Client\Exception
     */
    public function getItemRecommendationBulk($userId, $cookieId, array $context): Response
    {
        foreach ($context as $element) {
            $element->cookieId = $cookieId;
            $element->userId = $userId;
        }
        return $this->sendRequest(
            'getItemRecommendationBulk',
            "POST",
            array(
                'userId' => $userId,
                'cookieId' => $cookieId,
            ),
            $context
        );
    }


    /**
     * Simple test function to test without side effects whether the service is alive.
     * @param string $name
     * @return Response "Hello " + <code>$name</code>
     * @throws \Http\Client\Exception
     */
    public function test(string $name): Response
    {
        return $this->sendRequest('test', 'GET', array('name' => $name));
    }


    /**
     * @param string $methodName
     * @param string $httpMethod
     * @param array $queryStringParams
     * @param mixed $requestBody (anything that a json_encode can understand)
     * @return mixed|null
     * @throws \Http\Client\Exception
     */
    private function sendRequest(
        string $methodName,
        string $httpMethod = "GET",
        array $queryStringParams = null,
        $requestBody = null
    ): Response {
        $requestUrl = $this->config->getRemoteUrl()
            . '/'
            . $methodName
            . $this->getRequestQueryString($methodName, $queryStringParams);

        $plugins = [];

        if ($this->config->isForwardClientInfo()) {
            $headers = [];

            if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
                $headers["X-Forwarded-For"] = $_SERVER['REMOTE_ADDR'];
            }
            if (array_key_exists('HTTP_REFERER', $_SERVER)) {
                $headers["X-Original-Referer"] = $_SERVER['HTTP_REFERER'];
            }
            if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
                $headers["X-Device-User-Agent"] = $_SERVER['HTTP_USER_AGENT'];
            }
            if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
                $headers["X-Device-Accept-Language"] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            }

            $originalRequestUri = $this->guessOriginalRequestURI();

            if (!empty($originalRequestUri)) {
                $headers["X-Original-RequestUri"] = $originalRequestUri;
            } else {
                // could not detect original request URI, send SAPI name for debugging purposes
                $header["X-PhpServerAPIName"] = php_sapi_name();
            }

            $plugins[] = new HeaderAppendPlugin($headers);
        }

        if (in_array($methodName, $this->config->getRetryMethods())) {
            $plugins[] = new RetryPlugin(["retries" => $this->config->getRetry()]);
        }

        $client = new PluginClient($this->client, $plugins);

        $requestBody = $requestBody !== null
            ? json_encode($requestBody)
            : null;

        $uri = new Uri($requestUrl);
        $uri = $uri->withUserInfo($this->config->getUser(), $this->config->getPassword());

        $request = $this->messageFactory->createRequest($httpMethod, $uri, [], $requestBody);

        return $client->sendRequest($request);
    }

    protected function guessOriginalRequestURI(): string
    {
        $sapi_name = php_sapi_name();
        if ($sapi_name == 'cli') {
            // using CLI PHP
            return '';
        }
        $ssl = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
        $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $_SERVER['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $uri = $protocol . '://' . $host . $port . $_SERVER['REQUEST_URI'];
        return $uri;
    }

    /**
     * @param $methodName
     * @param $queryStringParams
     * @return string
     */
    protected function getRequestQueryString($methodName, $queryStringParams): string
    {
        $queryString = $queryStringParams
            ? \http_build_query($queryStringParams, "", '&')
            : '';

        if (!empty($queryString)) {
            $queryString = "&" . $queryString;
        }

        return "?method=" . \urlencode($methodName) . $queryString . "&_v=" . \urlencode(GravityClient::VERSION);
    }

    /**
     * @return HttpClient
     */
    public function getClient(): HttpClient
    {
        return $this->client;
    }
}