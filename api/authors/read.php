<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once '../../config/database.php';
    include_once '../../model/authors.php';
    
    // instantiate database and quotes object    
    $database = new Database();
    $db = $database->connect();
    
    // initialize object    
    $author = new authors($db);
    
    //query authors
    //determine what data we have available
    //using isset to keep reference errors from sending with the JSON data when the $_GET fails
    if(isset($_GET["id"])){
        $id = htmlspecialchars($_GET["id"]);
    }
    else{
        $id = "";
    }

    $stmt = $author->read($id);
    $num = $stmt->rowCount();

    //check if more than 0 record found
    if($num>0){
        //author array
        $author_arr = array();

        //retrive table conents
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //extract row
            extract($row);
            $author_item = array(
                "id" => $id,
                "author" => $author
            );

            array_push($author_arr, $author_item);
        }

        //set response code - 200 OK
        http_response_code(200);

        //show quotes date in json format
        echo json_encode($author_arr);
    }
    else{
        //set response code - 404 not found
        //http_response_code(404);

        //tell the user no authors found
        echo json_encode(array("message" => "authorId Not Found"));
    }
?>