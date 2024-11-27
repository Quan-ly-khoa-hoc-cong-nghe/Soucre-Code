<?php
class NhaTaiTro {
    private $conn;
    private $table = "NhaTaiTro";

    public $MaNhaTaiTro; // Sửa từ ID thành MaNhaTaiTro
    public $TenNhaTaiTro;
    public $DiaChi;
    public $SoDienThoai;
    public $Email;

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
            (TenNhaTaiTro, DiaChi, SoDienThoai, Email) 
            VALUES (:TenNhaTaiTro, :DiaChi, :SoDienThoai, :Email)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật nhà tài trợ
    public function update() {
        $query = "UPDATE " . $this->table . " 
            SET TenNhaTaiTro = :TenNhaTaiTro, DiaChi = :DiaChi, 
                SoDienThoai = :SoDienThoai, Email = :Email 
            WHERE MaNhaTaiTro = :MaNhaTaiTro";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhaTaiTro", $this->MaNhaTaiTro);
        $stmt->bindParam(":TenNhaTaiTro", $this->TenNhaTaiTro);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":Email", $this->Email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa nhà tài trợ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaNhaTaiTro = :MaNhaTaiTro";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhaTaiTro", $this->MaNhaTaiTro);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
