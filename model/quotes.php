<?php

class quotes{

    //database connection and table name
    private $conn;

    //object properties
    public $id;
    public $quote;
    public $authorId;
    public $categoryId;

    //constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //read quotes
    function read($id, $categoryId, $authorId, $random){
        $query = "";

        //only id provided
        if($id != "" && $categoryId == "" && $authorId == "")
        {
            $query = 'SELECT quotes.id, quote, author, category 
                        FROM quotes 
                        JOIN authors ON quotes.authorid = authors.id 
                        JOIN category ON quotes.categoryid = category.id 
                        WHERE quotes.id = "'.$id.'"
                        ORDER BY quotes.id asc';
        }
        //only author id provided
        else if($id == "" && $categoryId == "" && $authorId != "")
        {
            $query = 'SELECT quotes.id, quote, author, category 
                        FROM quotes 
                        JOIN authors ON quotes.authorid = authors.id 
                        JOIN category ON quotes.categoryid = category.id 
                        WHERE quotes.authorid = "'.$authorId.'"
                        ORDER BY quotes.id asc';
        }
        //only category id provided
        else if($id == "" && $categoryId != "" && $authorId == "")
        {
            $query = 'SELECT quotes.id, quote, author, category 
                        FROM quotes 
                        JOIN authors ON quotes.authorid = authors.id 
                        JOIN category ON quotes.categoryid = category.id 
                        WHERE quotes.categoryid = "'.$categoryId.'"
                        ORDER BY quotes.id asc';
        }
        //both category and author id provided
        else if($id == "" && $categoryId != "" && $authorId != "")
        {
            $query = 'SELECT quotes.id, quote, author, category 
                        FROM quotes 
                        JOIN authors ON quotes.authorid = authors.id 
                        JOIN category ON quotes.categoryid = category.id 
                        WHERE quotes.authorid = "'.$authorId.'" AND quotes.categoryid = "'.$categoryId.'"
                        ORDER BY quotes.id asc';
        }
        //select all query 
        else{
            $query = 'SELECT quotes.id, quote, author, category 
                    FROM quotes 
                    JOIN authors ON quotes.authorid = authors.id 
                    JOIN category ON quotes.categoryid = category.id
                    ORDER BY quotes.id asc';             
        }

        //If random is sent, do a replace on the query to add ORDER by random and limit the return response to 1 quote
        if($random)
        {
            $query = str_replace("ORDER BY quotes.id asc", "ORDER BY RAND() LIMIT 1", $query);
        }

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        return $stmt;
    }

    function create(){

        //clean data
        $this->quote = strip_tags($this->quote);
        $this->authorId = strip_tags($this->authorId);
        $this->categoryId = strip_tags($this->categoryId);

        $query = 'INSERT INTO quotes (`id`, `quote`, `authorid`, `categoryid`) 
                        VALUES (NULL, "'.$this->quote.'", "'.$this->authorId.'", "'.$this->categoryId.'")';
        
        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        $this->id = $this->conn->lastInsertId();

        return $stmt;
    }

    function update(){
        
        //clean data
        $this->quote = strip_tags($this->quote);
        $this->authorId = strip_tags($this->authorId);
        $this->categoryId = strip_tags($this->categoryId);

        $query = 'UPDATE quotes 
                    SET quote = "'.$this->quote.'", authorid = "'.$this->authorId.'", categoryid = "'.$this->categoryId.'" 
                    WHERE id = "'.$this->id.'"';

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        return $stmt->execute();
    }

    function delete(){
        $query = 'DELETE FROM quotes WHERE id = "'.$this->id.'"';

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
}

?>