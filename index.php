<?php 
header("Access-Control-Allow-Origin: *");

include_once "api/v1/config/database.php";

$database = new Database();

if(isset($_COOKIE["AUTH_TOKEN"]) && $_COOKIE["AUTH_TOKEN"] == $database->admin_token) {
    return include('main.html'); 
} 

return include('auth.html'); 

?>