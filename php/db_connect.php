<?php
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'abc123');
define('DB_HOST', 'localhost');
define('DB_NAME', 'lib');

class DbConnect {
 
    private $conn;
 
    function __construct() {
    }
 
    function connect() {
 
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
        if (mysqli_connect_errno())
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        
        
        return $this->conn;
    }
}
?>