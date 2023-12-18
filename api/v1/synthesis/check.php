<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Synthesis.php";
include_once "../objects/Version.php";

$database = new Database();
$db = $database->getConnection();

$synthesis = new Synthesis($db);
$version = new Version($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->mode) && isset($data->id)) {
        $id = $data->id;
        $mode = $data->mode;
        $text = $data->text;

        $stmt = $synthesis->check($mode, $id);
        $version->add("Проверка готовности синтеза. ID: " .$id);
        echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);

    } else {
        echo json_encode(array("message" => "Недостаточно данных."), JSON_UNESCAPED_UNICODE);
    }
} 

