<?php
ini_set("allow_url_fopen", 1);

if ( isset( $_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    echo $keyword;
    echo "<br>";
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=08a1a1fb2786ca00e3801c9048e262e9&tags='.$keyword.'&safe_search=3&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents);
    var_dump($obj->photos);
}
?>