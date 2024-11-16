<?php
class ChuTriPhien {
    private $conn;
    private $table = "ChuTriPhien";

    public $MaPhienHoiThao;
    public $MaNguoiThamGia;
    public $VaiTro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả chủ trì phiên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới chủ trì phiên
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaPhienHoiThao=:MaPhienHoiThao, MaNguoiThamGia=:MaNguoiThamGia, VaiTro=:VaiTro";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Cập nhật chủ trì phiên
    public function update() {
        $query = "UPDATE " . $this->table . " SET VaiTro=:VaiTro WHERE MaPhienHoiThao=:MaPhienHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Xóa chủ trì phiên
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaPhienHoiThao=:MaPhienHoiThao AND MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

        return $stmt->execute();
    }
}
?>
