<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TeachingDialogs.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$teaching = new TeachingDialogs($db);
$version = new Version($db);

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $stmt = $teaching->delete($id);
    $version->add("Обучение. Удаление диалога: " .$id);

    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
} else {

    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);

}








?>
