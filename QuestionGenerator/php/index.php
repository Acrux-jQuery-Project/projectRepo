<?php
/**
 * Created by PhpStorm.
 * User: macbookp
 */
    error_reporting(E_ALL);
    include_once "Template.php";

    $def = new CTemplate();
    $arrayp["id"] = "index";

    $def->mainFileConfig($arrayp,"one","main");
    $def->updateFileConfig("input,1,template","one","main","newValue1");
    $def->readFile("input,1,template","one","main");





?>