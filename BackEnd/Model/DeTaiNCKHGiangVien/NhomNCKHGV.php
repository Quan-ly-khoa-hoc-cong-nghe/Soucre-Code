<?php
class NhomNCKHGV {
    private $conn;
    private $table_name = "NhomNCKHGV";

    public $MaNhomNCKHGV;
    public $MaDeTaiNCKHGV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả nhóm NCKH
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một nhóm NCKH
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNhomNCKHGV = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaNhomNCKHGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới nhóm NCKH
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaNhomNCKHGV=:MaNhomNCKHGV, MaDeTaiNCKHGV=:MaDeTaiNCKHGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật nhóm NCKH
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaDeTaiNCKHGV=:MaDeTaiNCKHGV WHERE MaNhomNCKHGV=:MaNhomNCKHGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa nhóm NCKH
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaNhomNCKHGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
