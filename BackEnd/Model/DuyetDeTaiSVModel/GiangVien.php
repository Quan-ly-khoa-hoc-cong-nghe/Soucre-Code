<?php
class GiangVien {
    private $conn;
    private $table_name = "giangvien";

    public $MaGV;
    public $HoTenGV;
    public $NgaySinhGV;
    public $EmailGV;
    public $DiaChiGV;
    public $DiemNCKH = 0; // Giá trị mặc định
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả giảng viên
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY HoTenGV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    // Thêm giảng viên
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (HoTenGV, NgaySinhGV, EmailGV, DiaChiGV, DiemNCKH, MaKhoa) 
                    VALUES (:hoTenGV, :ngaySinhGV, :emailGV, :diaChiGV, :diemNCKH, :maKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':hoTenGV', $this->HoTenGV);
            $stmt->bindParam(':ngaySinhGV', $this->NgaySinhGV);
            $stmt->bindParam(':emailGV', $this->EmailGV);
            $stmt->bindParam(':diaChiGV', $this->DiaChiGV);
            $stmt->bindParam(':diemNCKH', $this->DiemNCKH);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi thêm giảng viên: " . $e->getMessage());
        }
    }

    // Cập nhật thông tin giảng viên
    public function update() {
        try {
            if (!$this->exists()) {
                throw new Exception("Giảng viên không tồn tại.");
            }

            $sql = "UPDATE " . $this->table_name . " 
                    SET HoTenGV = :hoTenGV, NgaySinhGV = :ngaySinhGV, EmailGV = :emailGV, 
                        DiaChiGV = :diaChiGV, DiemNCKH = :diemNCKH, MaKhoa = :maKhoa 
                    WHERE MaGV = :maGV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maGV', $this->MaGV);
            $stmt->bindParam(':hoTenGV', $this->HoTenGV);
            $stmt->bindParam(':ngaySinhGV', $this->NgaySinhGV);
            $stmt->bindParam(':emailGV', $this->EmailGV);
            $stmt->bindParam(':diaChiGV', $this->DiaChiGV);
            $stmt->bindParam(':diemNCKH', $this->DiemNCKH);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi cập nhật giảng viên: " . $e->getMessage());
        }
    }

    // Xóa giảng viên
    public function delete() {
        try {
            if (!$this->exists()) {
                throw new Exception("Giảng viên không tồn tại.");
            }

            $sql = "DELETE FROM " . $this->table_name . " WHERE MaGV = :maGV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maGV', $this->MaGV);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Lỗi xóa giảng viên: " . $e->getMessage());
        }
    }

    // Kiểm tra giảng viên có tồn tại hay không
    private function exists() {
        $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaGV = :maGV";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':maGV', $this->MaGV);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>
