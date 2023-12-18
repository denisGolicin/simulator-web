<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/TeachingDialogs.php";

$database = new Database();
$db = $database->getConnection();

$teaching = new TeachingDialogs($db);

if (isset($_GET['id'])) {
    $stmt = $teaching->readOne($_GET['id']);

    $dialog = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dialog) {
      
        echo json_encode($dialog, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("message" => "Диалог не найден."), JSON_UNESCAPED_UNICODE);
    }

    return true;
} 


if(isset($_GET['subjects']) && isset($_GET['classes'])){

    $stmt = $teaching->read($_GET['subjects'], $_GET['classes']);
    echo getData($stmt);

} else {
    
    $stmt = $teaching->readAll();
    echo getData($stmt);
}

function getData($stmt){
    $num = $stmt->rowCount();

    if ($num <= 0) {
        return json_encode(array("message" => "Диалоги для обучения не найдены."), JSON_UNESCAPED_UNICODE);
    }

    $json_arr = array();
    $json_arr["dialogs"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $item = array(
            "id" => $id,
            "question" => $question,
            "answer_correct" => $answer_correct,
            "answer_0" => $answer_0,
            "answer_1" => $answer_1,
            "answer_2" => $answer_2,
            "answer_3" => $answer_3,
            "audio_url" => $audio_url,
            "image_url" => $image_url,
            "subjects" => $subjects,
            "classes" => $classes,
            "status" => $status,
            "modified" => $modified,
        );
        array_push($json_arr["dialogs"], $item);
    }

    http_response_code(200);
    return json_encode($json_arr, JSON_UNESCAPED_UNICODE);
}


?>
