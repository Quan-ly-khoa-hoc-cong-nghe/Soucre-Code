<?php
class KeHoachHoiThao {
    private $conn;
    private $table = "KeHoachHoiThao";

    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $FileKeHoach;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới kế hoạch
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc, KinhPhi=:KinhPhi, FileKeHoach=:FileKeHoach, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
        $stmt->bindParam(":KinhPhi", $this->KinhPhi);
        $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật kế hoạch
    public function update() {
        $query = "UPDATE " . $this->table . " SET NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc, KinhPhi=:KinhPhi, FileKeHoach=:FileKeHoach WHERE MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
        $stmt->bindParam(":KinhPhi", $this->KinhPhi);
        $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa kế hoạch
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }
}
?>
