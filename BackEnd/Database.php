<?php
class Database {
    private $host = "localhost";
    private $db_name = "nghiencuukhoahoc";
    private $user = "root";
    private $password = "mysql";
    private $conn;

    public function getConn() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
            $this->conn->exec("set names utf8");
            return $this->conn;
        } catch(PDOException $exception) {
            echo "Error: " . $exception->getMessage();
            exit;
        }
    }
}
?>