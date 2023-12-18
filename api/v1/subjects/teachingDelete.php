<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TeachingSubjects.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();
$version = new Version($db);
$teaching = new TeachingSubjects($db);

if (isset($_GET['subjects_name']) && !empty($_GET['subjects_name']) && !preg_match('/\s/', $_GET['subjects_name'])) {
    $subjects_name = $_GET['subjects_name'];

    $stmt = $teaching->delete($subjects_name);
    $version->add("Обучение. Удаление предмета: " .$subjects_name);
    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}








?>
