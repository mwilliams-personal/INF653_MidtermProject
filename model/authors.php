<?php

class authors{

    //database connection and table name
    private $conn;

    //object properties
    public $id;
    public $author;

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
            $query = 'SELECT id, author 
                        FROM authors 
                        WHERE id = "'.$id.'"
                        ORDER BY id asc';
        }
        //select all query 
        else{
            $query = 'SELECT id, author 
                        FROM authors 
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
        $this->author = strip_tags($this->author);

        $query = 'INSERT INTO authors (`id`, `author`) 
                    VALUES (NULL, "'.$this->author.'")';
        
        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        $this->id = $this->conn->lastInsertId();

        return $stmt;
    }

    function update(){
        
        //clean inputs
        $this->author = strip_tags($this->author);

        $query = 'UPDATE authors 
                    SET author = "'.$this->author.'" 
                    WHERE id = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        return $stmt->execute();
    }

    function delete(){
        $query = 'DELETE FROM authors WHERE id = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        return $stmt->execute();
    }

    //Used for checking if an ID exists within a table before attempting to add a quote
    function id_Exists($id, $table)
    {
        //$id should be an int
        //$table should only be either 'category' or 'authors'

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

    //Used for finding if author id is used in the quote table
    function authorid_used()
    {
        $query = 'SELECT authorid FROM quotes WHERE authorid = "'.$this->id.'"';

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