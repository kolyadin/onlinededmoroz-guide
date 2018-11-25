<?php /** @noinspection PhpComposerExtensionStubsInspection */

$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

file_put_contents(__DIR__ . '/sample.txt', print_r($data, true));