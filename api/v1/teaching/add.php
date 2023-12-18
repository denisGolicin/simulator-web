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

if(isset($_GET['subjects']) && isset($_GET['classes'])){
    $subjects = $_GET['subjects'];
    $classes = $_GET['classes'];

    $stmt = $teaching->addExample($subjects, $classes);
    $version->add("Обучение. Новый диалог по " .$subjects ." за " .$classes ." класс");

    if($stmt) {
        echo json_encode(array("message" => true), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("message" => false), JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}








?>
