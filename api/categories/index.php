<?php
    //This serves as the QUOTES controller

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, categoryization, X-Requested-With");

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if ($requestMethod === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    }

    if($requestMethod == "GET"){
        include_once("read.php");
    }
    else if($requestMethod == "POST"){
        include_once("create.php");
    }
    else if($requestMethod == "PUT"){
        include_once("update.php");
    }
    else if($requestMethod == "DELETE"){
        include_once("delete.php");
    }
    else if($requestMethod != "OPTIONS"){
        //set response code - 404 not found
        http_response_code(404);

        //tell the user no quotes found
        echo json_encode(array("message" => "Request type <".$requestMethod."> not found."));
    }
?>