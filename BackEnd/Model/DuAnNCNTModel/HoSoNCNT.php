<?php
class HoSoNCNT {
    private $conn;
    private $table = "HoSoNCNT";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;
    public $MaDatHang;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một hồ sơ
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm hồ sơ mới
    public function add() {
        $query = "INSERT INTO " . $this->table . " (MaHoSo, NgayNop, FileHoSo, TrangThai, MaDatHang, MaKhoa) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->bindParam(2, $this->NgayNop);
        $stmt->bindParam(3, $this->FileHoSo);
        $stmt->bindParam(4, $this->TrangThai);
        $stmt->bindParam(5, $this->MaDatHang);
        $stmt->bindParam(6, $this->MaKhoa);

        return $stmt->execute();
    }

    // Cập nhật hồ sơ
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET NgayNop = ?, FileHoSo = ?, TrangThai = ?, MaDatHang = ?, MaKhoa = ? 
                  WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayNop);
        $stmt->bindParam(2, $this->FileHoSo);
        $stmt->bindParam(3, $this->TrangThai);
        $stmt->bindParam(4, $this->MaDatHang);
        $stmt->bindParam(5, $this->MaKhoa);
        $stmt->bindParam(6, $this->MaHoSo);

        return $stmt->execute();
    }

    // Xóa hồ sơ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);

        return $stmt->execute();
    }
}
?>
