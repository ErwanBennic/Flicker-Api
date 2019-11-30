
<head>
    <link href="assets/css/flicker-search.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Results Flicker</title>
</head>
<?php
require 'vendor/autoload.php';
ini_set("allow_url_fopen", 1);
$results = $total = $images = []; /* Instanciation de plusieurs tableaux */
$url = $keyword = $min_upload = $max_upload = $title = "";
$key = $safe_search = 0;
$media = "all";
$in_gallery = "false";

if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
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

    // Initialisation du client
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    /* Echappement des espaces pour éviter les erreurs php lors de la requête  */
    $keywordTrm = str_replace(' ', '%20', $keyword);
    $in_galleryTrm = str_replace(' ', '%20', $in_gallery);

    /* Requête */
    $json = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=03fcd9f31e9f53c06465ae89f019061e&text='.$keywordTrm.'&min_upload_date='.$min_upload.'&max_upload_date='.$max_upload.'&safe_search='.$safe_search.'&media='.$media.'&in_gallery='.$in_galleryTrm.'&format=json&nojsoncallback=1&extras=url_o';
    $contents = file_get_contents($json);
    $contents = utf8_encode($contents);
    $obj = json_decode($contents, true);

    // Vérifie si la recherche comporte des résultats -> évite de créer une base/collection inutilement
    if ($obj['photos']['total'] != 0){
        $isSearchEmpty = false;
    } else {
        $isSearchEmpty = true;
    }

    // Vérifie si la base existe, sinon la créée
    foreach ($client->listDatabases() as $databaseInfo) {
        if ($databaseInfo['name'] == "flickr"){
            $db = $client->flickr;
        } else {
            $db = new MongoDB\Database($manager,"flickr");
        }
    }

    // Récupération des données de la base
    $collections = $db->listCollections();
    $collectionNames = [];
    foreach ($collections as $collection) {
        $collectionNames[] = $collection->getName();
    }

    $exists = in_array($keyword, $collectionNames);
    if ($exists) {
        $collection = $db->$keyword;
        $collection = $collection->drop();
        $collection =  $db->createCollection($keyword);
        $collection = $db->$keyword;
    }else if (!$isSearchEmpty) {
        $collection =  $db->createCollection($keyword);
        $collection = $db->$keyword;
    }

    /* Vérification et insertion dans la base */
    if (!$isSearchEmpty) {
         foreach ($obj["photos"]["photo"] as $item) {
            $id = $item['id'];
            $owner = $item['owner'];
            $farm = $item['farm'];
            $server = $item['server'];
            $secret = $item['secret'];
            $title = $item['title'];

            /* Concaténation du lien */
            $url = "https://farm$farm.staticflickr.com/$server/{$id}_{$secret}.jpg";

            /* insertion dans la base de donnée */
             $insertOneResult = $collection->insertOne( [ 'url' => $url, 'title'=> $title, 'infos'=> $item ] );

            /* Affichage des images */
            $image = "<a href='https://www.flickr.com/photos/".$owner."/".$id."'><img class='image-size' src='".$url."' /></a>";
            array_push($images, $image);
           }
    }else {
       echo "Aucune correspondance.";
    }
}
?>

<div class="container">
    <div class="row">
        <h1>Recherche pour "<?php echo "$keyword" ?>"</h1>
    </div>
    <div class="row">
        <?php
        foreach ($images as $i) {
            echo $i;
        }
        ?>
    </div>
</div>
