<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Statistics.php";

$database = new Database();
$db = $database->getConnection();

$stats = new Statistics($db);

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$records_per_page = 100; 
$offset = ($page - 1) * $records_per_page;
$stmt = $stats->read($records_per_page, $offset);

$num = $stmt->rowCount();

if ($num > 0) {

    $items_arr = array();
    $items_arr["items"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $stats_item = array(
            "id" => $id,
            "name" => $name,
            "subjects" => $subjects,
            "classes" => $classes .$classes_letter,
            "answers_correct" => $answers_correct,
            "dialogs" => $dialogs,
            "precent" => $precent,
            "date" => $modified
        );
        array_push($items_arr["items"], $stats_item);
    }

    echo json_encode($items_arr);
}
 else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
}
?>
