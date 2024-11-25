<?php
class KeHoachNCNT {
    private $conn;
    private $table = "KeHoachNCNT";

    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $fileKeHoach;
    public $MaDuAn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một kế hoạch
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới kế hoạch
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET 
                  NgayBatDau = ?, NgayKetThuc = ?, KinhPhi = ?, fileKeHoach = ?, MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->NgayBatDau);
        $stmt->bindParam(2, $this->NgayKetThuc);
        $stmt->bindParam(3, $this->KinhPhi);
        $stmt->bindParam(4, $this->fileKeHoach);
        $stmt->bindParam(5, $this->MaDuAn);
        return $stmt->execute();
    }

    // Cập nhật kế hoạch
    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                  NgayBatDau = ?, NgayKetThuc = ?, KinhPhi = ?, fileKeHoach = ? 
                  WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->NgayBatDau);
        $stmt->bindParam(2, $this->NgayKetThuc);
        $stmt->bindParam(3, $this->KinhPhi);
        $stmt->bindParam(4, $this->fileKeHoach);
        $stmt->bindParam(5, $this->MaDuAn);
        return $stmt->execute();
    }

    // Xóa kế hoạch
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        return $stmt->execute();
    }
}
?>
