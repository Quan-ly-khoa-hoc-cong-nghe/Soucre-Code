<?php
class VaiTro {
    private $conn;
    private $table = "VaiTro";

    public $MaHoiThao;
    public $MaNguoiThamGia;
    public $VaiTro;
    public $ChuyenDe;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả vai trò
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới vai trò
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaHoiThao=:MaHoiThao, MaNguoiThamGia=:MaNguoiThamGia, VaiTro=:VaiTro, ChuyenDe=:ChuyenDe";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":ChuyenDe", $this->ChuyenDe);

        return $stmt->execute();
    }

    // Cập nhật vai trò
    public function update() {
        $query = "UPDATE " . $this->table . " SET VaiTro=:VaiTro, ChuyenDe=:ChuyenDe WHERE MaHoiThao=:MaHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":ChuyenDe", $this->ChuyenDe);

        return $stmt->execute();
    }

    // Xóa vai trò
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoiThao=:MaHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

        return $stmt->execute();
    }
}
?>
