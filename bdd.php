<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

//$dbExist = $manager.getMongo().getDBNames().indexOf("flickr");
//if ($dbExist == -1){
$db = new MongoDB\Database($manager,"flickr");
//}else{
$db = $client->flickr;
//}
$keyword = "sport";
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
    $collectionNames[] = $collection->getName();
}
$exists = in_array($keyword, $collectionNames);
if ($exists) {
    $collection = $db->$keyword;
}else {
    $collection =$db->createCollection($keyword);
    $collection =$db->$keyword;
}

//$collection = $db->createCollection( $keyword);
//$document = new MongoDB($manager,$collection,"");

$insertOneResult = $collection->insertOne([ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ]);

printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());


?>