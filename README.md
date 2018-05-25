GravityClient
==========

This is the PHP 7 + version of the former GravityClient.
The project adheres to PSR (1,2,4,7) standards, as a result the return values of the Client methods now return a PSR7 compliant response.

## Installation and Usage

- Use Composer to install the package (refer to the git project for now)
- The library needs a concrete PSR7 MessageFactory and a HTTPlug Client implementation for Client abstraction. These dependencies must come from your project. The HTTPlug project already offers ready made Clients and adapters for the most common http use cases (curl, socket, Guzzle etc) for more info consult the [docs](http://docs.php-http.org/en/latest/index.html).
- For the impatient see the Samples for a Curl and Guzzle based "hello world" example
 
 ## UPGRADE FROM 1.X Gravityrd client
 
 - You must provide a PSR7 MessageFactory and a HTTPlug Client implementation along. You can either simply require those in your composer project or pass to the client explicitly.
   - Client implementations: http://docs.php-http.org/en/latest/clients.html
   - Message Factory implementations: http://docs.php-http.org/en/latest/httplug/users.html
   - Or you can make your own, check the HTTPlug documentation for interface definitions
  - Exception Handling:
    - The GravityException is no more. The Client will throw if configuration or initialization fails the proper sub component exception. 
    - When making Requests the HTTPlug Exceptions will be thrown, see: [docs](http://docs.php-http.org/en/latest/httplug/exceptions.html?highlight=exceptions)
- Currently we only support synchronous clients which will always return with a PSR7 Request (the exact classes depend on the Message Factory you proviced).
    - However you can still pass the async flag to the request that will behave exactly as 1.X used to. 
- Client Configuration:
    - Gravity Config now only takes care of the Gravity specific settings,
    client specific settings such as the old: verifyPeer are now must be done in the Client that you use (so in this case you must inject your client) see the example bellow:
    ```php
    // The Message and Stream factories will be  
    // dicovered in this case, and you only pass options.
    // For the options available see the Client's documentation
    
    $options = [
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_SSL_VERIFYPEER => false
    ];
  
    $curlClient = new Http\Client\Curl\Client(null,null,$options);
    ```   
    The removed config options are:
     * timeoutSeconds
     * verifyPeer
     * verbose
  - Client Configuration became immutable, you must use the constructor to set it
     
