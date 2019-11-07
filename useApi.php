<?php
ini_set("allow_url_fopen", 1);
$results = $farm = $server = $id = $secret = $total = []; /* Instanciation de plusieurs tableaux en une ligne */
$url = "";
$key = 0;
$min_upload = "";
$max_upload = "";
$safe_search = 0;
$media = "all";
$keyword = "";
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
    echo "Recherche pour \"$keyword\".";
    echo "<br>";
    /* Echappement des espaces pour éviter les erreurs php lors de la requête  */
    $keywordTrm = str_replace(' ', '%20', $keyword);
    $in_galleryTrm = str_replace(' ', '%20', $in_gallery);
    /* Url */
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=e2577250b047535e7b6bb994febaab53&text='.$keywordTrm.'&min_upload_date='.$min_upload.'&max_upload_date='.$max_upload.'&safe_search='.$safe_search.'&media='.$media.'&in_gallery='.$in_galleryTrm.'&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents, true);

    /* Vérification si le champ est bien rempli et si c'est un objet */
    /*if (!empty($obj->photos->photo[$key]) && is_object($obj->photos->photo[$key])) {
        $results["total"] = $obj->photos->total;*/
         foreach ($obj["photos"]["photo"] as $item) {
            $farmInt = $item['farm'];
            $serverStr = $item['server'];
            $idStr = $item['id'];
            $secretStr = $item['secret'];
            /* Concaténation du lien */
            $url = "https://farm$farmInt.staticflickr.com/$serverStr/{$idStr}_{$secretStr}.jpg";
            $results["url"] = $url;

            /* Affichage des images */
            echo "<img src='".$url."' />";
            
            //var_dump($farmInt);
            //var_dump($results);
        }
    /*}else {
        echo "Aucune correspondance.";
    }*/
}

?>