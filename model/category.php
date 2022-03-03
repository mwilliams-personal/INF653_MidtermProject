<?php

class category{

    //database connection and table name
    private $conn;

    //object properties
    public $id;
    public $category;

    //constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //read quotes
    function read($id){
        $query = '';

        //only id provided
        if($id != "")
        {
            $query = 'SELECT id, category 
                        FROM category 
                        WHERE id = "'.$id.'"
                        ORDER BY id asc';
        }
        //select all query 
        else{
            $query = 'SELECT id, category 
                        FROM category 
                        ORDER BY id asc';                                  
        }

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        return $stmt;
    }

    function create(){

        //clean inputs
        $this->category = strip_tags($this->category);

        $query = 'INSERT INTO category (`id`, `category`) 
                    VALUES (NULL, "'.$this->category.'")';
        
        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        $this->id = $this->conn->lastInsertId();

        return $stmt;
    }

    function update(){
        
        //clean inputs
        $this->category = strip_tags($this->category);

        $query = 'UPDATE category 
                    SET category = "'.$this->category.'" 
                    WHERE id = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        return $stmt->execute();
    }

    function delete(){
        $query = 'DELETE FROM category WHERE id = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        return $stmt->execute();
    }

    //Used for checking if an ID exists within a table before attempting to add a quote
    function id_Exists($id, $table)
    {
        //$id should be an int
        //$table should only be either 'category' or 'category'

        $query = 'SELECT id FROM '.$table.' WHERE id = "'.$id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    //Used for finding if category id is used in the quote table
    function categoryid_used()
    {
        $query = 'SELECT categoryid FROM quotes WHERE categoryid = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}

?>