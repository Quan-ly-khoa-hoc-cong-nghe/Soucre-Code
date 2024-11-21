<?php
class PhienHoiThao {
    private $conn;
    private $table = "PhienHoiThao";

    public $MaPhienHoiThao;
    public $TenPhienHoiThao;
    public $ThoiGianBatDau;
    public $ThoiGianKetThuc;
    public $MoTa;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả phiên hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới phiên hội thảo
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaPhienHoiThao=:MaPhienHoiThao, TenPhienHoiThao=:TenPhienHoiThao, ThoiGianBatDau=:ThoiGianBatDau, ThoiGianKetThuc=:ThoiGianKetThuc, MoTa=:MoTa, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":TenPhienHoiThao", $this->TenPhienHoiThao);
        $stmt->bindParam(":ThoiGianBatDau", $this->ThoiGianBatDau);
        $stmt->bindParam(":ThoiGianKetThuc", $this->ThoiGianKetThuc);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật phiên hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " SET TenPhienHoiThao=:TenPhienHoiThao, ThoiGianBatDau=:ThoiGianBatDau, ThoiGianKetThuc=:ThoiGianKetThuc, MoTa=:MoTa, MaHoiThao=:MaHoiThao WHERE MaPhienHoiThao=:MaPhienHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":TenPhienHoiThao", $this->TenPhienHoiThao);
        $stmt->bindParam(":ThoiGianBatDau", $this->ThoiGianBatDau);
        $stmt->bindParam(":ThoiGianKetThuc", $this->ThoiGianKetThuc);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa phiên hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaPhienHoiThao=:MaPhienHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);

        return $stmt->execute();
    }
}
?>
