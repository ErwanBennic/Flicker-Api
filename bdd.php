<?php

require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$db = new MongoDB\Database($manager,"flickr");
$collection = new MongoDB\Collection($manager,$db,"photos");
//$collection = $client->flickr->photos;

$result = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );

echo "Inserted with Object ID '{$result->getInsertedIid()}'";