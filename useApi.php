<?php
require 'vendor/autoload.php';

$m = new MongoClient(); // connexion
$db = $m->selectDB("Flicker-Api");