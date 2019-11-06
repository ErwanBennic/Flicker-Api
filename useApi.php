<?php
ini_set("allow_url_fopen", 1);
$results = $farm = $server = $id = $secret = $total = $urlArray = []; /* Instanciation de plusieurs tableaux en une ligne */
$key = 0;
$min_upload = "";
$max_upload = "";
$safe_search = null;
$media = "";
$keyword = "";
$in_gallery = "";

if ( isset( $_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    echo "Recherche pour \"$keyword\".";
    echo "<br>";
    /* Echappement des espaces pour éviter les erreurs php lors de la requête  */
    $keywordTrm = str_replace(' ', '', $keyword);
    $in_galleryTrm = str_replace(' ', '', $in_gallery);
    /* Url */
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=974d84d5c54c0d30e46e31aee0df863f&text='.$keywordTrm.'&min_upload_date='.$min_upload.'&max_upload_date='.$max_upload.'&safe_search='.$safe_search.'&media='.$media.'&in_gallery='.$in_galleryTrm.'&format=json&nojsoncallback=1';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents);
    /* Vérification si le champ est bien rempli et si c'est un objet */
    if (!empty($obj->photos->photo[$key]) && is_object($obj->photos->photo[$key])) {
        $results["total"] = $obj->photos->total;
        while ($key < 50) {
            $results[$key]["farm"] = $obj->photos->photo[$key]->farm;
            $results[$key]["server"] = $obj->photos->photo[$key]->server;
            $results[$key]["id"] = $obj->photos->photo[$key]->id;
            $results[$key]["secret"] = $obj->photos->photo[$key]->secret;
            /* Recuperation des valeurs obligatoire */
            $farmInt = $results[$key]["farm"];
            $serverStr = $results[$key]["server"];
            $idStr = $results[$key]["id"];
            $secretStr = $results[$key]["secret"];
            /* Concaténation du lien */
            $urlArray[] = "https://farm$farmInt.staticflickr.com/$serverStr/{$idStr}_{$secretStr}.jpg";
            $results[$key]["url"] = $urlArray;
            /* Réinstanciation du tableau pour éviter les répétitions */
            $urlArray = [];
            $key++;
        }
    }else {
        echo "Aucune correspondance.";
    }
}
var_dump($results);
?>