<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once '../../config/database.php';
    include_once '../../model/category.php';
    
    // instantiate database and quotes object    
    $database = new Database();
    $db = $database->connect();
    
    // initialize object    
    $category = new category($db);
    
    //query categorys
    //determine what data we have available
    //using isset to keep reference errors from sending with the JSON data when the $_GET fails
    if(isset($_GET["id"])){
        $id = htmlspecialchars($_GET["id"]);
    }
    else{
        $id = "";
    }

    $stmt = $category->read($id);
    $num = $stmt->rowCount();

    //check if more than 0 record found
    if($num>0){
        //category array
        $category_arr = array();

        //retrive table conents
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //extract row
            extract($row);
            $category_item = array(
                "id" => $id,
                "category" => $category
            );

            array_push($category_arr, $category_item);
        }

        //set response code - 200 OK
        http_response_code(200);

        //show quotes date in json format
        if(sizeof($category_arr) > 1){
            echo json_encode($category_arr);
        }
        else{
            echo json_encode($category_arr[0]);
        }
    }
    else{
        //set response code - 404 not found
        //http_response_code(404);

        //tell the user no categorys found
        echo json_encode(array("message" => "categoryId Not Found"));
    }
?>