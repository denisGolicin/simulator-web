<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TestingSubjects.php";

$database = new Database();
$db = $database->getConnection();

$subjects = new TestingSubjects($db);

$stmt = $subjects->read();
$num = $stmt->rowCount();

if ($num > 0) {

    $json_arr = array();
    $json_arr["testing_subjects"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $item = array(
            "id" => $id,
            "subjects_name" => $subjects_name,
            "status" => $status,
            "modified" => $modified,
        );
        array_push($json_arr["testing_subjects"], $item);
    }

    http_response_code(200);
    echo json_encode($json_arr);
}
 else {
    http_response_code(404);
    echo json_encode(array("message" => "Предметы для тестирования не найдены."), JSON_UNESCAPED_UNICODE);
}
?>
