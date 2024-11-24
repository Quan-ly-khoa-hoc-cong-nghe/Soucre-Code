<?php
class HoSoNCKHSV {
    private $conn;
    private $table_name = "HoSoNCKHSV";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY NgayNop ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm hồ sơ mới
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaHoSo, NgayNop, FileHoSo, TrangThai, MaKhoa) 
                    VALUES (:maHoSo, :ngayNop, :fileHoSo, :trangThai, :maKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':ngayNop', $this->NgayNop);
            $stmt->bindParam(':fileHoSo', $this->FileHoSo);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật hồ sơ
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET NgayNop = :ngayNop, FileHoSo = :fileHoSo, TrangThai = :trangThai, MaKhoa = :maKhoa 
                    WHERE MaHoSo = :maHoSo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':ngayNop', $this->NgayNop);
            $stmt->bindParam(':fileHoSo', $this->FileHoSo);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Xóa hồ sơ
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaHoSo = :maHoSo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
