<?php
class SinhVienNCKHSV {
    private $conn;
    private $table_name = "SinhVienNCKHSV";

    public $MaNhomNCKHSV;
    public $MaSinhVien;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaSinhVien ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaSinhVien) VALUES (:maNhomNCKHSV, :maSinhVien)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " SET MaSinhVien = :maSinhVien WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
