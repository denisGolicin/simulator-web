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
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id) && isset($data->question) && isset($data->answer_0)
        && isset($data->answer_correct) && isset($data->answer_1)
        && isset($data->answer_2) && isset($data->answer_3)) {
        $id = $data->id;
        $question = $data->question;
        $answer_0 = $data->answer_0;
        $answer_correct = $data->answer_correct;
        $answer_1 = $data->answer_1;
        $answer_2 = $data->answer_2;
        $answer_3 = $data->answer_3;

        $stmt = $teaching->edit($id, $question, $answer_correct, $answer_0, $answer_1, $answer_2, $answer_3);
        $version->add("Обучение. Редактирование диалога ID: " .$id);
        echo json_encode(array("message" => $stmt), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("message" => "Недостаточно данных."), JSON_UNESCAPED_UNICODE);
    }
}
?>
