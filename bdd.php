<?php
require 'useApi.php';
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");


$dbExist = db.getMongo().getDBNames().indexOf("flickr");
if ($dbExist == -1){
    $db = new MongoDB\Database($manager,"flickr");
}else{
    $db = $client->flickr;
}

$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
    $collectionNames[] = $collection->getName();
}
$exists = in_array($keyword, $collectionNames);
if ($exists == True) {
    $collection = $client->flickr->$keyword;
}else {
    $collection = new MongoDB\Collection($manager,$db,$keyword);
}
$document = new MongoDB($manager,$collection,"");

$result = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );

echo "Inserted with Object ID '{$result->getInsertedIid()}'";