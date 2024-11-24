<?php
class KeHoachDTCS {
    private $conn;
    private $table = "KeHoachDTCS";

    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $FileKeHoach;
    public $MaDTCS;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch đào tạo cơ sở
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một kế hoạch đào tạo cơ sở
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm kế hoạch đào tạo cơ sở
    public function add() {
        $query = "INSERT INTO " . $this->table . " (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaDTCS) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayBatDau);
        $stmt->bindParam(2, $this->NgayKetThuc);
        $stmt->bindParam(3, $this->KinhPhi);
        $stmt->bindParam(4, $this->FileKeHoach);
        $stmt->bindParam(5, $this->MaDTCS);

        return $stmt->execute();
    }

    // Cập nhật kế hoạch đào tạo cơ sở
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET NgayBatDau = ?, NgayKetThuc = ?, KinhPhi = ?, FileKeHoach = ? 
                  WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayBatDau);
        $stmt->bindParam(2, $this->NgayKetThuc);
        $stmt->bindParam(3, $this->KinhPhi);
        $stmt->bindParam(4, $this->FileKeHoach);
        $stmt->bindParam(5, $this->MaDTCS);

        return $stmt->execute();
    }

    // Xóa kế hoạch đào tạo cơ sở
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);

        return $stmt->execute();
    }
}
?>
