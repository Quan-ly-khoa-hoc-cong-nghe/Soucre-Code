<?php
class DienGiaPhien {
    private $conn;
    private $table = "DienGiaPhien";

    public $MaPhienHoiThao;
    public $MaNguoiThamGia;
    public $ChuyenDe;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả diễn giả phiên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới diễn giả phiên
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaPhienHoiThao=:MaPhienHoiThao, MaNguoiThamGia=:MaNguoiThamGia, ChuyenDe=:ChuyenDe";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":ChuyenDe", $this->ChuyenDe);

        return $stmt->execute();
    }

    // Cập nhật diễn giả phiên
    public function update() {
        $query = "UPDATE " . $this->table . " SET ChuyenDe=:ChuyenDe WHERE MaPhienHoiThao=:MaPhienHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":ChuyenDe", $this->ChuyenDe);

        return $stmt->execute();
    }

    // Xóa diễn giả phiên
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaPhienHoiThao=:MaPhienHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

        return $stmt->execute();
    }
}
?>
