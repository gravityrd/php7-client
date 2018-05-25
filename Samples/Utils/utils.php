<?php
declare(strict_types=1);

use Gravityrd\GravityClient\ClientConfiguration;
use Gravityrd\GravityClient\GravityClient;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use function GuzzleHttp\Psr7\copy_to_string;

function printResponse(Response $response, string $prefix = "", $default = "empty body")
{
    $str = copy_to_string($response->getBody());

    if (empty($str)){
        $str = $default;
    }

    echo PHP_EOL . $prefix . $str . PHP_EOL;
}

function make_client(ClientConfiguration $config, HttpClient $clientImpl = null, MessageFactory $messageFactory = null)
{
    try {
        return new GravityClient($config, $clientImpl, $messageFactory);

    } catch (\Exception $ex) {
        die(PHP_EOL . '[' . get_class($ex) . ']::' . $ex->getMessage() . PHP_EOL);
    }
}

function get_config(): array
{
    $serviceName = getenv('YUSP_SERVICE_USERNAME') !== false
        ? getenv('YUSP_SERVICE_USERNAME')
        : "yourusername";

    $servicePassword = getenv('YUSP_SERVICE_PASSWORD') !== false
        ? getenv('YUSP_SERVICE_PASSWORD')
        : "secret";

    // Format hint: 'https://<CUSTOMERID>-<SERVERLOCATION>.gravityrd-services.com/grrec-<CUSTOMERID>-war/WebshopServlet';
    $serviceUrl = getenv('YUSP_SERVICE_URL') !== false
        ? getenv('YUSP_SERVICE_URL')
        : "https://customerid-bud.gravityrd-services.com/grrec-customerid-war/WebshopServlet";

    return [$serviceName, $servicePassword, $serviceUrl];
}


/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_event(GravityClient $client): Response
{
    $event = new \Gravityrd\GravityClient\GravityEvent();
    $event->eventType = 'BUY';
    $event->itemId = 'IdOfTheBoughtBook';
    $event->userId = 'testUser1';
    $event->cookieId = 'roll1234';
    $unitPrice = new \Gravityrd\GravityClient\GravityNameValue("unitPrice","60.5");
    $quantity = new \Gravityrd\GravityClient\GravityNameValue("quantity","1");
    $orderId = new \Gravityrd\GravityClient\GravityNameValue("orderId","order_123456789");
    $event->nameValues = array($unitPrice,$quantity,$orderId);
    $async = false;
    return $client->addEvent($event, $async);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_events(GravityClient $client): Response
{
    $event1 = new \Gravityrd\GravityClient\GravityEvent();
    $event1->eventType = 'BUY';
    $event1->itemId = 'IdOfTheBoughtBook';
    $event1->userId = 'testUser1';
    $event1->cookieId = 'roll1234';
    $event1->time = time();
    $unitPrice1 = new \Gravityrd\GravityClient\GravityNameValue("unitPrice","60.5");
    $quantity1 = new \Gravityrd\GravityClient\GravityNameValue("quantity","1");
    $orderId1 = new \Gravityrd\GravityClient\GravityNameValue("orderId","order_123456789");
    $event1->nameValues = array($unitPrice1,$quantity1,$orderId1);
    $event2 = new \Gravityrd\GravityClient\GravityEvent();
    $event2->eventType = 'VIEW';
    $event2->itemId = 'fffffff';
    $event2->userId = 'testUser1';
    $event2->cookieId = 'roll1234';
    $event2->time = time();
    $unitPrice2 = new \Gravityrd\GravityClient\GravityNameValue("unitPrice","30");
    $quantity2 = new \Gravityrd\GravityClient\GravityNameValue("quantity","3");
    $orderId2 = new \Gravityrd\GravityClient\GravityNameValue("orderId","order_1234567333");
    $event2->nameValues = array($unitPrice2,$quantity2,$orderId2);
    $eventsToAdd = array($event1,$event2);
    $async = false;
    return $client->addEvents($eventsToAdd, $async);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_item_recommendation(GravityClient $client): Response
{

    $context = new Gravityrd\GravityClient\RecommendationContext();
    $context->scenarioId = "ROLL_TEST";
    return $client->getItemRecommendation("353","sdfsdf-sfsdfsd", $context);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_item_recommendation_bulk(GravityClient $client): Response
{
    $context1 = new Gravityrd\GravityClient\RecommendationContext();
    $context1->scenarioId = "ROLL_TEST";
    $context1->numberLimit=2;

    $context2 = new Gravityrd\GravityClient\RecommendationContext();
    $context2->scenarioId = "ROLL_TEST";
    $context2->numberLimit=1;

    $recommendationContextArray = array(
        0 => $context1,
        1 => $context2
    );
    $itemRecommendations = null;
    return $client->getItemRecommendationBulk("roll1234", "3432423423ac323333", $recommendationContextArray );
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_user(GravityClient $client): Response
{
    $user = new \Gravityrd\GravityClient\GravityUser();
    $user->userId = 'roll1234';
    $user->hidden = false;
    $user->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('gender', 'male'),
        new \Gravityrd\GravityClient\GravityNameValue('zip', '213324'),
        new \Gravityrd\GravityClient\GravityNameValue('country', 'Germany'));

    return $client->addUser($user, true);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_users(GravityClient $client): Response
{
    $user = new \Gravityrd\GravityClient\GravityUser();
    $user->userId = 'rereroll1234';
    $user->hidden = false;
    $user->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('gender', 'male'),
        new \Gravityrd\GravityClient\GravityNameValue('zip', '213324'),
        new \Gravityrd\GravityClient\GravityNameValue('country', 'Germany'));
    $user2 = new \Gravityrd\GravityClient\GravityUser();
    $user2->userId = 'nemroll1234';
    $user2->hidden = false;
    $user2->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('gender', 'male'),
        new \Gravityrd\GravityClient\GravityNameValue('zip', '33333'),
        new \Gravityrd\GravityClient\GravityNameValue('country', 'valami'));

    $users=array($user,$user2);
    return $client->addUsers($users, true);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_item(GravityClient $client): Response
{
    $item1 = new \Gravityrd\GravityClient\GravityItem();
    $item1->itemId = 'roll123';
    $item1->title = 'Millenium Falcon';
    $item1->hidden = false;
    $item1->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '2000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '105'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '4.7'));
    $isAsync = true;

    return $client->addItem($item1, $isAsync);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_add_items(GravityClient $client): Response
{
    $item1 = new \Gravityrd\GravityClient\GravityItem();
    $item1->itemId = 'roll222';
    $item1->title = 'Executor';
    $item1->hidden = false;
    $item1->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '9000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '105'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '4.7'));
    $item2 = new \Gravityrd\GravityClient\GravityItem();
    $item2->itemId = 'roll999';
    $item2->title = 'Death Star';
    $item2->hidden = false;
    $item2->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '5000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '333'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '2'));
    $isAsync = true;
    $items=array($item1,$item2);
    return $client->addItems($items, $isAsync);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_update_item(GravityClient $client): Response
{
    $item1 = new \Gravityrd\GravityClient\GravityItem();
    $item1->itemId = 'roll222';
    $item1->title = 'Vegas';
    $item1->hidden = false;
    $item1->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '44000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '905'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '4.7'));
    return $client->updateItem($item1);
}

/**
 * @param GravityClient $client
 * @return Response
 * @throws \Http\Client\Exception
 */
function test_update_items(GravityClient $client): Response
{
    $item1 = new \Gravityrd\GravityClient\GravityItem();
    $item1->itemId = 'roll222';
    $item1->title = 'Béla';
    $item1->hidden = false;
    $item1->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '44000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '905'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '4.7'));

    $item2 = new \Gravityrd\GravityClient\GravityItem();
    $item2->itemId = 'roll123';
    $item2->title = 'Géza';
    $item2->hidden = false;
    $item2->nameValues = array(new \Gravityrd\GravityClient\GravityNameValue('price', '499000'),
        new \Gravityrd\GravityClient\GravityNameValue('categoryId', '905'),
        new \Gravityrd\GravityClient\GravityNameValue('screenSize', '4.7'));
    $items=array($item1,$item2);
    return $client->updateItems($items);
}