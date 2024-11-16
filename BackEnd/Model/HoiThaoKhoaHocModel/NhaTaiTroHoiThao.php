<?php
class NhaTaiTro {
    private $conn;
    private $table = "NhaTaiTro";

    public $TenNhaTaiTro;
    public $DiaChi;
    public $SoDienThoai;
    public $Email;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả nhà tài trợ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới nhà tài trợ
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET TenNhaTaiTro=:TenNhaTaiTro, DiaChi=:DiaChi, SoDienThoai=:SoDienThoai, Email=:Email, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật nhà tài trợ
    public function update() {
        $query = "UPDATE " . $this->table . " SET DiaChi=:DiaChi, SoDienThoai=:SoDienThoai, Email=:Email WHERE TenNhaTaiTro=:TenNhaTaiTro AND MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa nhà tài trợ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE TenNhaTaiTro=:TenNhaTaiTro AND MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }
}
?>
