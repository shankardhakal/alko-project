<?php

require_once __DIR__ . '/vendor/autoload.php';


    $config = require __DIR__ . "/config.php";
    $medoo = new \Medoo\Medoo($config['database']);
    $importer = new \Task\ProductImporter($medoo);

    $importer->persistData(  $importer->getDataFromAlko());

