<?php
declare(strict_types=1);

/*
 * Example usage of the YuspClient
 * Change the constants to your account information
 */
require_once "vendor/autoload.php";
require_once "../Utils/utils.php";

use Gravityrd\GravityClient\ClientConfiguration;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\NetworkException;
use Http\Client\Exception\RequestException;

$localConfig = get_config();
$config = new ClientConfiguration(...$localConfig);

/*
* Simples usage is to depend on the Service Discovery, that should work ... in most cases
*/
$client = make_client($config);
run_tests($client);

/*
 * Or you could inject your client's dependencies directly
 */
$guzzleClient = new Http\Adapter\Guzzle6\Client(new GuzzleHttp\Client());
$messageFactory = new \Http\Message\MessageFactory\GuzzleMessageFactory();
$client = make_client($config, $guzzleClient, $messageFactory);
run_tests($client);

function run_tests(\Gravityrd\GravityClient\GravityClient $client)
{
    $i = 0;
    try {
        printResponse($client->test("Your Name"), $i++ . ": ");
        /*
         * WARNING!!!
         * The methods bellow will add sample events, users to your system.
         * DO NOT USE THEM IN PRODUCTION!
         * DO NOT USE THEM WITHOUT MODIFYING THE UNDERLYING DATA!
         * USE IT ONLY FOR DEVELOPMENT.
         */
//        printResponse(test_add_event($client), $i++ . ": ");
//        printResponse(test_add_events($client), $i++ . ": ");
//        printResponse(test_add_item($client), $i++ . ": ");
//        printResponse(test_add_items($client), $i++ . ": ");
//        printResponse(test_add_user($client), $i++ . ": ");
//        printResponse(test_add_users($client), $i++ . ": ");
//        printResponse(test_update_item($client), $i++ . ": ");
//        printResponse(test_update_items($client), $i++ . ": ");
//        printResponse(test_item_recommendation($client), $i++ . ": ");
//        printResponse(test_item_recommendation_bulk($client), $i++ . ": ");
    }catch (NetworkException $exception){
        die("Network related error: " . $exception->getMessage());
    }catch (HttpException $exception){
        die("The response status does not indicate sucess: " . $exception->getMessage());
    }catch (RequestException $exception) {
        die("Invalid Request: " . $exception->getMessage());
    }catch (\Http\Client\Exception $e) {
        die("Client instantiation failed (detailed information is in the inner exception): " . $e->getMessage());
    }catch (\Exception $exception){
        die("Unexpected exception: " . $exception->getMessage());
    }

}