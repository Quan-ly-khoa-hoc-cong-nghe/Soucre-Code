<?php
class SinhVien {
    private $conn;
    private $table_name = "SinhVien";

    public $MaSinhVien;
    public $TenSinhVien;
    public $EmailSV;
    public $sdtSV;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sinh viên
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenSinhVien ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    // Thêm sinh viên
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaSinhVien, TenSinhVien, EmailSV, sdtSV, MaKhoa) 
                    VALUES (:maSinhVien, :tenSinhVien, :emailSV, :sdtSV, :maKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':tenSinhVien', $this->TenSinhVien);
            $stmt->bindParam(':emailSV', $this->EmailSV);
            $stmt->bindParam(':sdtSV', $this->sdtSV);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi thêm sinh viên: " . $e->getMessage());
        }
    }

    // Cập nhật sinh viên
    public function update() {
        try {
            if (!$this->exists()) {
                throw new Exception("Sinh viên không tồn tại.");
            }

            $sql = "UPDATE " . $this->table_name . " 
                    SET TenSinhVien = :tenSinhVien, EmailSV = :emailSV, sdtSV = :sdtSV, MaKhoa = :maKhoa 
                    WHERE MaSinhVien = :maSinhVien";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':tenSinhVien', $this->TenSinhVien);
            $stmt->bindParam(':emailSV', $this->EmailSV);
            $stmt->bindParam(':sdtSV', $this->sdtSV);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi cập nhật sinh viên: " . $e->getMessage());
        }
    }

    // Xóa sinh viên
    public function delete() {
        try {
            if (!$this->exists()) {
                throw new Exception("Sinh viên không tồn tại.");
            }

            $sql = "DELETE FROM " . $this->table_name . " WHERE MaSinhVien = :maSinhVien";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi xóa sinh viên: " . $e->getMessage());
        }
    }

    // Kiểm tra sinh viên có tồn tại hay không
    private function exists() {
        $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>
