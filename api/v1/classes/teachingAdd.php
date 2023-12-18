<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TeachingClasses.php";
include_once "../objects/Version.php";


$database = new Database();
$db = $database->getConnection();

$teaching = new TeachingClasses($db);
$version = new Version($db);

if (isset($_GET['classes_name']) && !empty($_GET['classes_name']) && !preg_match('/\s/', $_GET['classes_name']) &&
    isset($_GET['subjects_name']) && !empty($_GET['subjects_name']) && !preg_match('/\s/', $_GET['subjects_name'])) {
    $class_name = $_GET['classes_name'];
    $subjects_name = $_GET['subjects_name'];

    $stmt = $teaching->add($subjects_name, $class_name);
    $version->add("Обучение. Новый класс по ".$subjects_name ." класс - " .$class_name);

    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}

?>
