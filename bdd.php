<?php







$dbExist = db.getMongo().getDBNames().indexOf("flickr");
if ($dbExist == -1){
    $db = new MongoDB\Database($manager,"flickr");
}else{
    $collection = $client->flickr;
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
$keyword = "45";
//$collection = $db->createCollection( $keyword);
//$document = new MongoDB($manager,$collection,"");

$insertOneResult = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );

printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());


echo "Inserted with Object ID '{$result->getInsertedIid()}'";

?>