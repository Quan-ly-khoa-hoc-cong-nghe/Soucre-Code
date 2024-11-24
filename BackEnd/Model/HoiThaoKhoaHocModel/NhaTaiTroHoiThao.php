<?php
class NhaTaiTro {
    private $conn;
    private $table = "NhaTaiTro";

    public $ID;
    public $TenNhaTaiTro;
    public $DiaChi;
    public $LoaiTaiTro;
    public $SoTien;
    public $SoDienThoai;
    public $Email;
    public $MaHoiThao;
    public $ThoiGianDongGop;

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

    // Thêm nhà tài trợ mới
    public function add() {
        $query = "INSERT INTO " . $this->table . " 
            (TenNhaTaiTro, DiaChi, LoaiTaiTro, SoTien, SoDienThoai, Email, MaHoiThao) 
            VALUES (:TenNhaTaiTro, :DiaChi, :LoaiTaiTro, :SoTien, :SoDienThoai, :Email, :MaHoiThao)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật nhà tài trợ
    public function update() {
        $query = "UPDATE " . $this->table . " 
            SET TenNhaTaiTro = :TenNhaTaiTro, DiaChi = :DiaChi, LoaiTaiTro = :LoaiTaiTro, 
                SoTien = :SoTien, SoDienThoai = :SoDienThoai, Email = :Email 
            WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":ID", $this->ID);
        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa nhà tài trợ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":ID", $this->ID);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
