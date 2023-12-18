<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$version = new Version($db);
$stmt = $version->read();
echo json_encode(array("version" => (int)$stmt), JSON_UNESCAPED_UNICODE);
