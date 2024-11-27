<?php
class Khoa {
    private $conn;
    private $table_name = "Khoa";

    public $MaKhoa;
    public $TenKhoa;
    public $VanPhongKhoa; // Sửa tên thuộc tính cho đúng với bảng

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả khoa
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenKhoa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    // Thêm khoa
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (TenKhoa, VanPhongKhoa) 
                    VALUES (:tenKhoa, :vanPhongKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tenKhoa', $this->TenKhoa);
            $stmt->bindParam(':vanPhongKhoa', $this->VanPhongKhoa); // Sửa tên tham số cho đúng
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi thêm khoa: " . $e->getMessage());
        }
    }

    // Cập nhật khoa
    public function update() {
        try {
            // Kiểm tra xem MaKhoa có tồn tại không
            if (!$this->exists()) {
                throw new Exception("Mã khoa không tồn tại");
            }

            $sql = "UPDATE " . $this->table_name . " 
                    SET TenKhoa = :tenKhoa, VanPhongKhoa = :vanPhongKhoa 
                    WHERE MaKhoa = :maKhoa";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            $stmt->bindParam(':tenKhoa', $this->TenKhoa);
            $stmt->bindParam(':vanPhongKhoa', $this->VanPhongKhoa); // Sửa tên tham số cho đúng
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi cập nhật khoa: " . $e->getMessage());
        }
    }

    // Xóa khoa
    public function delete() {
        try {
            // Kiểm tra xem MaKhoa có tồn tại không
            if (!$this->exists()) {
                throw new Exception("Mã khoa không tồn tại");
            }

            $sql = "DELETE FROM " . $this->table_name . " WHERE MaKhoa = :maKhoa";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi xóa khoa: " . $e->getMessage());
        }
    }

    // Kiểm tra xem MaKhoa có tồn tại không
    private function exists() {
        $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaKhoa = :maKhoa";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maKhoa', $this->MaKhoa);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>
