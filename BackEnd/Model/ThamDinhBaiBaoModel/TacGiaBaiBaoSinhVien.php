<?php
class TacGiaSinhVien {
    private $conn;
    private $table_name = "TacGiaSinhVien";

    public $MaBaiBao;
    public $MaSinhVien;
    public $VaiTro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm tác giả sinh viên
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaBaiBao=:MaBaiBao, MaSinhVien=:MaSinhVien, VaiTro=:VaiTro";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Lấy tất cả tác giả sinh viên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật vai trò của tác giả sinh viên
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET VaiTro=:VaiTro WHERE MaBaiBao=:MaBaiBao AND MaSinhVien=:MaSinhVien";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Xóa tác giả sinh viên
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaBaiBao=:MaBaiBao AND MaSinhVien=:MaSinhVien";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);

        return $stmt->execute();
    }
}
?>
