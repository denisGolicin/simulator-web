<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TestingClasses.php";

$database = new Database();
$db = $database->getConnection();

$classes = new TestingClasses($db);

$stmt = $classes->read();
$num = $stmt->rowCount();

if ($num > 0) {

    $json_arr = array();
    $json_arr["testing_classes"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $item = array(
            "id" => $id,
            "classes_name" => $classes_name,
            "status" => $status,
            "modified" => $modified,
        );
        array_push($json_arr["testing_classes"], $item);
    }

    http_response_code(200);
    echo json_encode($json_arr);
}
 else {
    http_response_code(404);
    echo json_encode(array("message" => "Классы для тестирования не найдены."), JSON_UNESCAPED_UNICODE);
}
?>
