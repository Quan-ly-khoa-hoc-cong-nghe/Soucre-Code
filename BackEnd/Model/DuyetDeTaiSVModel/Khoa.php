<?php
class Khoa {
    private $conn;
    private $table_name = "Khoa";

    public $MaKhoa;
    public $TenKhoa;
    public $VanPhongKHoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenKhoa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (TenKhoa, VanPhongKHoa) 
                    VALUES (:tenKhoa, :vanPhongKHoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tenKhoa', $this->TenKhoa);
            $stmt->bindParam(':vanPhongKHoa', $this->VanPhongKHoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET TenKhoa = :tenKhoa, VanPhongKHoa = :vanPhongKHoa 
                    WHERE MaKhoa = :maKhoa";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            $stmt->bindParam(':tenKhoa', $this->TenKhoa);
            $stmt->bindParam(':vanPhongKHoa', $this->VanPhongKHoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaKhoa = :maKhoa";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
