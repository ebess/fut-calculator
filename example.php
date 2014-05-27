<?php

    require_once __DIR__ . "/autoload.php";

    // test data cristiano ronaldo
    $assetId = 20801;
    $resourceId = 1711296833;
    $definitionId = 100684097;

    // start calculator with the asset id and get the resource id
    $calc = new Fut\Calculator($assetId);
    echo $calc->getResourceId() . PHP_EOL . PHP_EOL;

    // init the calc without asset id, set resource id and get all information
    $calc = new Fut\Calculator();
    $calc->setResourceId($resourceId);
    echo "current version: " . $calc->getCurrentVersion() . PHP_EOL;
    echo "asset id: " . $calc->getAssetId() . PHP_EOL;
    echo "resource id (version 1): " . $calc->getResourceId() . PHP_EOL;
    echo "definition id: " . $calc->getDefinitionId() . PHP_EOL . PHP_EOL;

    // same as last but only based on the definition id
    $calc = new Fut\Calculator();
    $calc->setDefinitionId($definitionId);
    echo "current version: " . $calc->getCurrentVersion() . PHP_EOL;
    echo "asset id: " . $calc->getAssetId() . PHP_EOL;
    echo "resource id (version 2): " . $calc->getResourceId(2) . PHP_EOL;
    echo "definition id: " . $calc->getDefinitionId() . PHP_EOL . PHP_EOL;
