<?php /** @noinspection PhpComposerExtensionStubsInspection */

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

require_once __DIR__ . '/vendor/autoload.php';

$client = new Client([
    'base_uri' => 'https://dynamicmedia.agency',
    'headers' => [
        'X-USE-CACHE' => 'no',
        'X-AUTHORIZE-TOKEN' => '_____CUSTOMER_SUPERSECRETTOKEN_____'
    ]
]);

//1 ребенок с 1-ой фоткой
$params = require_once __DIR__ . '/1rebenok_1photo.php';

//1 ребенок с 2-мя фотками
$params = require_once __DIR__ . '/1rebenok_2photo.php';

//2 ребенка по одной фотке на каждого + 1 общее
$params = require_once __DIR__ . '/2rebenka.php';

try {
    $response = $client->post("/public/api/projects/onlinededmoroz/jobs", [
        'json' => [
            'params' => $params
        ]]);

    if (202 !== $response->getStatusCode()) {
        throw new \RuntimeException("Unexpected response code status received, expected [ 202 ], received [ {$response->getStatusCode()} ]");
    }
    //Render job successfully accepted by platform and placed in queue
    //Please check periodically this location for get job status:
    $checkLocationUrl = $response->getHeader('location')[0];
    $responseBody = json_decode($response->getBody()->getContents(), true);
    print_r($responseBody);
} catch (ConnectException $e) {
    echo 'Network problem, please retry';
} catch (ClientException $e) {
    switch ($e->getCode()) {
        case 400:
            //Problems with params or json body or other client staff
            //Please check $e->getMessage() method for clarification
            echo $e->getMessage();
            break;
        case 401:
            //Wrong X-AUTHORIZE-TOKEN header
            //Recheck it or contact technical team
            echo $e->getMessage();
            break;
        case 403:
            //Problem with user rights
            //If you received this, contact technical team
            echo $e->getMessage();
            break;
        case 404:
            echo $e->getMessage();
            //Wrong project id provided or project no exists
            break;
    }
} catch (\Exception $e) {
    //Some unexpected exception catched
    echo $e->getMessage();
}
//dump($out);
//$out->getBody()->getContents();
//$out->getHeader('X-Websocket-Monitor-Channel');