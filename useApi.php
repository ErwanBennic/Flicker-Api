<?php
ini_set("allow_url_fopen", 1);

if ( isset( $_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    echo $keyword;
    echo "<br>";
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=28984d098e9946c2c42b87eac57a678b&tags='.$keyword.'&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents);

    $i = 0;
    $id = [];
    while ($i < 50) {
        array_push($id, $obj->photos->photo[$i]->id);
        $i++;
    }
    https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg
}

var_dump($id);
?>