<?php 
  class Database {
    
    protected $host;
    protected $uername;
    protected $password;
    protected $db_name;

    function __construct(){
        $jawsurl = getenv('JAWSDB_URL');
        $dbparts = parse_url($jawsurl);
        
        // DB Params
        $this->host = $dbparts['host'];
        $this->username = $dbparts['user'];
        $this->password = $dbparts['pass'];
        $this->db_name = ltrim($dbparts['path'],'/');
    }
    
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