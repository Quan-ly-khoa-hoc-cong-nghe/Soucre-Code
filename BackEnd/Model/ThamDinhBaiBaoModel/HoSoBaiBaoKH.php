<?php
class HoSoBaiBaoKH {
    private $conn;
    private $table_name = "HoSoBaiBaoKH";

    public $MaHoSo;
    public $TrangThai;
    public $MaNguoiDung;
    public $NgayNop;
    public $MaTacGia;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm hồ sơ bài báo
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaHoSo=:MaHoSo, TrangThai=:TrangThai, MaNguoiDung=:MaNguoiDung, NgayNop=:NgayNop, MaTacGia=:MaTacGia, MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":MaTacGia", $this->MaTacGia);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        return $stmt->execute();
    }

    // Lấy tất cả hồ sơ bài báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật hồ sơ bài báo
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TrangThai=:TrangThai, MaNguoiDung=:MaNguoiDung, NgayNop=:NgayNop, MaTacGia=:MaTacGia, MaKhoa=:MaKhoa WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":MaTacGia", $this->MaTacGia);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        return $stmt->execute();
    }

    // Xóa hồ sơ bài báo
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);

        return $stmt->execute();
    }
}
?>
