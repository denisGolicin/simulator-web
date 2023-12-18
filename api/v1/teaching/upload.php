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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['id'] && $_FILES['file']) {
        $id = $_POST['id'];
        $file = $_FILES['file'];

        $stmt = $teaching->upload($id, $file);
        $version->add("Обучение. Загрузка картинки ID: " .$id);
        echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("message" => "Недостаточно данных. - " .$data->id), JSON_UNESCAPED_UNICODE);
    }
} 

?>
