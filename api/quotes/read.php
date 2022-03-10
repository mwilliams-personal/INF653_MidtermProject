<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once '../../config/database.php';
    include_once '../../model/quotes.php';
    
    // instantiate database and quotes object    
    $database = new Database();
    $db = $database->connect();
    
    // initialize object    
    $quotes = new quotes($db);
    
    //query quotes
    //determine what data we have available
    //using isset to keep reference errors from sending with the JSON data when the $_GET fails
    if(isset($_GET["id"])){
        $id = htmlspecialchars($_GET["id"]);
    }
    else{
        $id = "";
    }
    
    if(isset($_GET["categoryId"])){
        $categoryId = htmlspecialchars($_GET["categoryId"]);
    }
    else{
        $categoryId = "";
    }
    
    if(isset($_GET["authorId"])){
        $authorId = htmlspecialchars($_GET["authorId"]);
    }
    else{
        $authorId = "";
    }

    $stmt = $quotes->read($id, $categoryId, $authorId);
    $num = $stmt->rowCount();

    //check if more than 0 record found
    if($num>0){
        //quotes array
        $quotes_arr = array();

        //retrive table conents
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //extract row
            extract($row);
            $quotes_item = array(
                "id" => $id,
                "quote" => $quote,
                "author" => $author,
                "category" => $category
            );

            array_push($quotes_arr, $quotes_item);
        }

        //set response code - 200 OK
        http_response_code(200);

        //show quotes date in json format
        if(sizeof($quotes_arr) > 1){
            echo json_encode($quotes_arr);
        }
        else{
            echo json_encode($quotes_arr[0]);
        }
    }
    else{
        //set response code - 404 not found
        //http_response_code(404);

        //tell the user no quotes found
        echo json_encode(array("message" => "No Quotes Found"));
    }
?>