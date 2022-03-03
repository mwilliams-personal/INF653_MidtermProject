<?php

    // required headersheader("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // include database and object files
    include_once '../../config/database.php';
    include_once '../../model/quotes.php';

    $database = new Database();
    $db = $database->connect();
    $quote = new quotes($db);

    //get the posted data

    $data = json_decode(file_get_contents("php://input"));

    //make sure the data is not empty
    if(!empty($data->id) && !empty($data->quote) && !empty($data->authorId) && !empty($data->categoryId)){
        //set the quote property values
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->authorId = $data->authorId;
        $quote->categoryId = $data->categoryId;
        
        //Check if the id exists in the table at all
        if(!$quote->id_Exists($quote->id, "quotes")){ 
            //set response code - 400 bad request
            //http_response_code(400);
            
            //tell the user
            echo json_encode(array("message" => "No Quotes Found"));

        }
        //Check for the author id
        else if(!$quote->id_Exists($quote->authorId, "authors")){ 
            //set response code - 400 bad request
            //http_response_code(400);
            
            //tell the user
            echo json_encode(array("message" => "authorId Not Found"));

        }
        //Check for the category id
        else if(!$quote->id_Exists($quote->categoryId, "category")){ 
            //set response code - 400 bad request
            //http_response_code(400);
            
            //tell the user
            echo json_encode(array("message" => "categoryId Not Found"));

        }
        else{
            if($quote->update()){
                //set response code - 202 accepted
                http_response_code(202);
                
                //tell the user
                echo json_encode(array("id" => $quote->id, "quote" => $quote->quote, "authorId" => $quote->authorId, "categoryId" => $quote->categoryId));
            }
            else{
                //set response code - 503 service unavailable
                //http_response_code(503);
                
                //tell the user
                echo json_encode(array("message" => "Unable to update quote."));
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