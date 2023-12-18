<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TestingClasses.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$testing = new TestingClasses($db);
$version = new Version($db);

if (isset($_GET['classes_name']) && !empty($_GET['classes_name']) && !preg_match('/\s/', $_GET['classes_name']) &&
    isset($_GET['subjects_name']) && !empty($_GET['subjects_name']) && !preg_match('/\s/', $_GET['subjects_name']) &&
    isset($_GET['classes_name_new']) && !empty($_GET['classes_name_new']) && !preg_match('/\s/', $_GET['classes_name_new'])
    ) {
    $class_name = $_GET['classes_name'];
    $class_name_new = $_GET['classes_name_new'];
    $subjects_name = $_GET['subjects_name'];

    $stmt = $testing->edit($subjects_name, $class_name, $class_name_new);
    $version->add("Тестирование. Редактирование класса " .$class_name ."->" .$class_name_new ." за " .$subjects_name);
    echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}

?>
