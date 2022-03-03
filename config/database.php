<?php 
  class Database {
    
    private $jawsurl = getenv('JAWSDB_URL');
    private $dbparts = parse_url($jawsurl);
    
    // DB Params
    private $host = $dbparts['host'];
    private $username = $dbparts['user'];
    private $password = $dbparts['pass'];
    private $db_name = ltrim($dbparts['path'],'/');
    public $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }