<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TestingSubjects.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$testing = new TestingSubjects($db);
$version = new Version($db);

if (isset($_GET['current_name']) && !empty($_GET['current_name']) && !preg_match('/\s/', $_GET['current_name']) &&
    isset($_GET['new_name']) && !empty($_GET['new_name']) && !preg_match('/\s/', $_GET['new_name'])) {

    $current_name = $_GET['current_name'];
    $new_name = $_GET['new_name'];

    $stmt = $testing->edit($current_name, $new_name);
    $version->add("Тестирование. Редактирование предмета: " .$current_name ."->" .$new_name);

    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}








?>
