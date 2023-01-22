<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'vendor/autoload.php';
include 'config.php';
include 'MysqliDb.php';
include 'ImportMysql.php';

$db = new MysqliDb(host,username, password,dbname);
$type= "top_500";

$obj = new ImportMysql($db,$configSheet[$type]);
$data = $obj->readFile('import_excel',7);
echo "hi";
pr($data);
?>
