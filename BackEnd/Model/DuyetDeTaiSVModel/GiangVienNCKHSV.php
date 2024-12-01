<?php
class GiangVienNCKHSV {
    private $conn;
    private $table_name = "GiangVienNCKHSV"; // Sửa tên bảng cho đúng

    public $MaNhomNCKHSV;
    public $MaGV;
    public $VaiTro;  // Thêm VaiTro vào model

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả dữ liệu
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaGV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm dữ liệu mới
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaGV, VaiTro) VALUES (:maNhomNCKHSV, :maGV, :vaiTro)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maGV', $this->MaGV);
            $stmt->bindParam(':vaiTro', $this->VaiTro);  // Thêm VaiTro vào bind
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật dữ liệu
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " SET MaGV = :maGV, VaiTro = :vaiTro WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maGV', $this->MaGV);
            $stmt->bindParam(':vaiTro', $this->VaiTro);  // Thêm VaiTro vào bind
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Xóa dữ liệu
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
