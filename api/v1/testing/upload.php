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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['id'] && $_FILES['file']) {
        $id = $_POST['id'];
        $file = $_FILES['file'];

        $stmt = $testing->upload($id, $file);
        $version->add("Тестирование. Загрузка картинки ID: " .$id);
        echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("message" => "Недостаточно данных. - " .$data->id), JSON_UNESCAPED_UNICODE);
    }
} 

?>
