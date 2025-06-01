<?php
if(!defined('DB_SERVER')){
    require_once("../initialize.php");
}
class DBConnection{

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    
    public $conn;
    
    public function __construct(){

        if (!isset($this->conn)) {
            
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if (!$this->conn) {
                echo 'Cannot connect to database server';
                exit;
            }            
        }    
        
    }
    
    public function __destruct(){
        // Periksa apakah koneksi masih valid sebelum menutup
        if ($this->conn && $this->conn instanceof mysqli) {
            try {
                // if (!$this->conn->connect_errno && $this->conn->ping()) {
                //     $this->conn->close();
                // }
            } catch (Exception $e) {
                // Jika error, abaikan saja - koneksi mungkin sudah ditutup
            }
        }
    }
}
?>