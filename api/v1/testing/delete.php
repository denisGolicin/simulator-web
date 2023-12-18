<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TestingDialogs.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$testing = new TestingDialogs($db);
$version = new Version($db);

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $stmt = $testing->delete($id);
    $version->add("Тестирование. Удаление диалога: " .$id);
    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
} else {

    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);

}








?>
