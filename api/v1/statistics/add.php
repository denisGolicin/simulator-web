<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../objects/Statistics.php";


$database = new Database();
$db = $database->getConnection();

$stats = new Statistics($db);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $class_letter = $_POST['class_letter'];
    $answers_correct = $_POST['answers_correct'];
    $dialogs = $_POST['dialogs'];

    sendTelegram("🔄 - SferaSchool POST: " .$_SERVER['REMOTE_ADDR'] 
    ."\nИмя: " .$name
    ."\nПредмет: " .$subject
    ."\nКласс: " .$class
    ."\nБуква: " .$class_letter
    ."\nОтветы: " .$answers_correct
    ."\nДиалоги: " .$dialogs, "console");

    $precent = $answers_correct / $dialogs * 100;

    $stmt = $stats->add($name, $subject, $class, $class_letter, $answers_correct, $dialogs, round($precent, 1));
    echo json_encode(array("message" => (bool)$stmt), JSON_UNESCAPED_UNICODE);
    return true;
}

if (isset($_GET['name']) && !empty($_GET['name']) &&
    isset($_GET['subject']) && !empty($_GET['subject']) &&
    isset($_GET['class']) && !empty($_GET['class']) && 
    isset($_GET['class_letter']) && !empty($_GET['class_letter']) &&
    isset($_GET['dialogs']) && !empty($_GET['dialogs']) &&
    isset($_GET['answers_correct']) && !empty($_GET['answers_correct'])) {


    //sendTelegram("🔄 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] ."\nКодировка: " .mb_detect_encoding($_GET['subject']), "console");

    $name = $_GET['name'];
    $subject = $_GET['subject'];
    $class = $_GET['class'];
    $class_letter = $_GET['class_letter'];
    $answers_correct = $_GET['answers_correct'];
    $dialogs = $_GET['dialogs'];
    
    $name = mb_convert_encoding($name, "UTF-8", mb_detect_encoding($_GET['name']));
    $subject = mb_convert_encoding($subject, "UTF-8", mb_detect_encoding($_GET['subject']));
    $class_letter = mb_convert_encoding($class_letter, "UTF-8", mb_detect_encoding($_GET['class_letter']));

    sendTelegram("🔄 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] ."\nКодировка: " .mb_detect_encoding($_GET['subject']), "console");

    // $name = mb_convert_encoding($name, "UTF-8");
    // $subject = mb_convert_encoding($subject, "UTF-8");
    // $class_letter = mb_convert_encoding($class_letter, "UTF-8");
    

    $precent = $answers_correct / $dialogs * 100;

    $stmt = $stats->add($name, $subject, $class, $class_letter, $answers_correct, $dialogs, $precent);
    sendTelegram("🔄 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] 
    ."\nИмя: " .$name
    ."\nПредмет: " .$subject
    ."\nКласс: " .$class
    ."\nБуква: " .$class_letter
    ."\nОтветы: " .$answers_correct
    ."\nДиалоги: " .$dialogs, "console");

    echo json_encode(array("message" => (bool)$stmt), JSON_UNESCAPED_UNICODE);
    
} else {
    echo json_encode(array("message" => "Нет данных."), JSON_UNESCAPED_UNICODE);
    sendTelegram("🔄 - SferaSchool: " .$_SERVER['REMOTE_ADDR'] ." Нет данных", "console");
}

function sendTelegram($message, $console)
{
    $token = '5458155555:AAFvGNcxewNki4bM5vjUxsbki7TDNcTmY18';
    if($console == 'error'){
        $chatId = '-1001868993888';
    } else {
        $chatId = '-1002116362205';
    }

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);

    $context = stream_context_create(['socket' => ['bindto' => '0:0']]);
    $response = file_get_contents($url, false, $context);

}

?>
