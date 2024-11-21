<?php
class HoiThao {
    private $conn;
    private $table = "HoiThao";

    public $MaHoiThao;
    public $TenHoiThao;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $DiaDiem;
    public $SoLuongThamDu;
    public $MaKeHoachSoBo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới hội thảo
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaHoiThao=:MaHoiThao, TenHoiThao=:TenHoiThao, NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc, DiaDiem=:DiaDiem, SoLuongThamDu=:SoLuongThamDu, MaKeHoachSoBo=:MaKeHoachSoBo";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":TenHoiThao", $this->TenHoiThao);
        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
        $stmt->bindParam(":DiaDiem", $this->DiaDiem);
        $stmt->bindParam(":SoLuongThamDu", $this->SoLuongThamDu);
        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);

        return $stmt->execute();
    }

    // Cập nhật hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " SET TenHoiThao=:TenHoiThao, NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc, DiaDiem=:DiaDiem, SoLuongThamDu=:SoLuongThamDu, MaKeHoachSoBo=:MaKeHoachSoBo WHERE MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":TenHoiThao", $this->TenHoiThao);
        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
        $stmt->bindParam(":DiaDiem", $this->DiaDiem);
        $stmt->bindParam(":SoLuongThamDu", $this->SoLuongThamDu);
        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);

        return $stmt->execute();
    }

    // Xóa hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }
}
?>
