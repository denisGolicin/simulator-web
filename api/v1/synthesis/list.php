<?php

header('Content-type: application/json');
 
$curl = curl_init();
 
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://apihost.ru/api/v1/speaker',
    CURLOPT_RETURNTRANSFER  =>  1,
    CURLOPT_POSTFIELDS =>  json_encode(array('server'=> 1)),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer <TOKEN>',
        'Content-Type: application/json',
    ),
 
));
 
$response = curl_exec($curl);
 
curl_close($curl);
 
echo $response;

?>