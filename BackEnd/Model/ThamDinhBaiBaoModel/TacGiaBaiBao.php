<?php
class TacGiaBaiBao {
    private $conn;
    private $table_name = "TacGiaBaiBao";

    public $MaTacGia;
    public $MaBaiBao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm tác giả bài báo
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaTacGia=:MaTacGia, MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaTacGia", $this->MaTacGia);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }

    // Lấy tất cả tác giả bài báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật thông tin tác giả bài báo
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaBaiBao=:MaBaiBao WHERE MaTacGia=:MaTacGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTacGia", $this->MaTacGia);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }

    // Xóa tác giả bài báo
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaTacGia=:MaTacGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTacGia", $this->MaTacGia);

        return $stmt->execute();
    }
}
?>
