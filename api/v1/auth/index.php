<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once "../config/database.php";
$database = new Database();

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;

if(isset($_COOKIE["AUTH_TOKEN"]) && $_COOKIE["AUTH_TOKEN"] == $database->admin_token) {

    echo json_encode(array('success' => true, 'message' => 'Successful authorization!'));
    return true;
    
} 

if($data->login == $database->admin_login && $data->password == $database->admin_password){

    setcookie('AUTH_TOKEN', $database->admin_token, time() + 3600, "/");
    echo json_encode(array('success' => true, 'message' => 'Successful authorization!'));
    return true;
    exit();

} else {

    echo json_encode(array('success' => false, 'message' => 'Invalid username or password!'));
    return true;
    exit();
}


?>