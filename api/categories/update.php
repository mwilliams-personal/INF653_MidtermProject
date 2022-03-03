<?php

    // required headersheader("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, categoryization, X-Requested-With");

    // include database and object files
    include_once '../../config/database.php';
    include_once '../../model/category.php';

    $database = new Database();
    $db = $database->connect();
    $category = new category($db);

    //get the posted data

    $data = json_decode(file_get_contents("php://input"));

    //make sure the data is not empty
    if(!empty($data->id) && !empty($data->category)){
        //set the category property values
        $category->id = $data->id;
        $category->category = $data->category;
        
        //Check if the id exists in the table at all
        if(!$category->id_Exists($category->id, "category")){ 
            //set response code - 400 bad request
            //http_response_code(400);
            
            //tell the user
            echo json_encode(array("message" => "categoryId Not Found"));

        }
        else{
            if($category->update()){
                //set response code - 202 accepted
                http_response_code(202);
                
                //tell the user
                echo json_encode(array("id" => $category->id, "author" => $category->author));
            }
            else{
                //set response code - 503 service unavailable
                //http_response_code(503);
                
                //tell the user
                echo json_encode(array("message" => "Unable to update category."));
            }
        }
    }
    else{
        //Tell the user the data is incomplete
        //set response code - 400 bad request
        //http_response_code(400);
        
        //tell the user
        echo json_encode(array("message" => "Missing Required Parameters"));
    }
?>