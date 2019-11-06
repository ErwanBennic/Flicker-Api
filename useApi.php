<?php
ini_set("allow_url_fopen", 1);
$keyword = "";
$results = [];
$farm = [];
$server = [];
$id = [];
$secret = [];
$key = 1;

if ( isset( $_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    echo $keyword;
    echo "<br>";
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=974d84d5c54c0d30e46e31aee0df863f&tags='.$keyword.'&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents);

    while ($key < 50) {
        $results[$key]["farm"] = $obj->photos->photo[$key]->farm;
        $results[$key]["server"] = $obj->photos->photo[$key]->server;
        $results[$key]["id"] = $obj->photos->photo[$key]->id;
        $results[$key]["secret"] = $obj->photos->photo[$key]->secret;
        $key++;
    }
}

var_dump($results);
?>