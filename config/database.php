<?php 
  class Database {
    
    public $conn;

    // DB Connect
    public function connect() {

      $jawsurl = getenv('JAWSDB_URL');
      $dbparts = parse_url($jawsurl);
        
      // DB Params
      $host = $dbparts['host'];
      $username = $dbparts['user'];
      $password = $dbparts['pass'];
      $db_name = ltrim($dbparts['path'],'/');

      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }