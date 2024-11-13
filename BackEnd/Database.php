<?php
class Database  
{
    private $host = "localhost";
    private $db_name = "nghiencuukhoahoc";
private $user = "root";
private $password = "mysql";
public $conn;
public function getConn ()
{
    $this->conn = null;
    try{
        $this->conn = new PDO("mysql:host= ". $this-> host. "; dbname = ".$this->db_name, $this->user, $this->password);
        $this->conn->exec("set names utf8");
    }
    catch (PDOException $e) {
        echo "Loi ket noi" . $e->getMessage();
    }
    return $this->conn;
}

}
?>