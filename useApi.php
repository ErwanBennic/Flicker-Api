<head>
    <link href="assets/css/flicker-search.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Results Flicker</title>
</head>
<?php
ini_set("allow_url_fopen", 1);
$results = $farm = $server = $id = $secret = $total = []; /* Instanciation de plusieurs tableaux */
$url = $keyword = $min_upload = $max_upload = "";
$key = $safe_search = 0;
$media = "all";
$in_gallery = "false";

if (isset($_POST['keyword'])) {
    if (isset($_POST['galery'])) {
        $in_gallery = $_POST['galery'];
    }
    if (isset($_POST['dateStart'])) {
        $min_upload = $_POST['dateStart'];
    }
    if (isset($_POST['dateEnd'])) {
        $max_upload = $_POST['dateEnd'];
    }
    if (isset($_POST['search'])) {
        $safe_search = $_POST['search'];
    }
    if (isset($_POST['photos'])) {
        $media = $_POST['photos'];
    }
    if (isset($_POST['videos'])) {
        $media = $_POST['videos'];
    }
    if ((isset($_POST['photos'])) && (isset($_POST['videos']))) {
        $media = "all";
    }

    $keyword = $_POST['keyword'];

    /* Html temporaire */
    echo "<div class='container'>";
    echo "<div class='row pt-4'>";
    echo "<h1>Recherche pour \"$keyword\".</h1>";
    echo "</div>";
    echo "<br>";
    echo "<div class='row results'>";
    /* Echappement des espaces pour éviter les erreurs php lors de la requête  */
    $keywordTrm = str_replace(' ', '%20', $keyword);
    $in_galleryTrm = str_replace(' ', '%20', $in_gallery);

    /* Url */
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=03fcd9f31e9f53c06465ae89f019061e&text='.$keywordTrm.'&min_upload_date='.$min_upload.'&max_upload_date='.$max_upload.'&safe_search='.$safe_search.'&media='.$media.'&in_gallery='.$in_galleryTrm.'&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents, true);

    /* Vérification si le champ est bien rempli et si c'est un objet */
     foreach ($obj["photos"]["photo"] as $item) {
        $farmInt = $item['farm'];
        $serverStr = $item['server'];
        $idStr = $item['id'];
        $secretStr = $item['secret'];

        /* Concaténation du lien */
        $url = "https://farm$farmInt.staticflickr.com/$serverStr/{$idStr}_{$secretStr}.jpg";
        $results["url"] = $url;

        /* Affichage des images */
        echo "<img class='image-size' src='".$url."' />";

        /*var_dump($results);*/ // Résultats (liens) à insérer dans mongodb
    }
        echo "</div>";
     echo "</div>";
}
?>