<?php
class TacGiaGiangVien {
    private $conn;
    private $table_name = "TacGiaGiangVien";

    public $MaBaiBao;
    public $MaGV;
    public $VaiTro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm tác giả giảng viên
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaBaiBao=:MaBaiBao, MaGV=:MaGV, VaiTro=:VaiTro";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu


        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);


        $stmt->bindParam(":MaGV", $this->MaGV);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Lấy tất cả tác giả giảng viên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật vai trò của tác giả giảng viên
    public function update() {



        $query = "UPDATE " . $this->table_name . " SET VaiTro=:VaiTro WHERE MaBaiBao=:MaBaiBao AND MaGV=:MaGV";


        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":MaGV", $this->MaGV);
        $stmt->bindParam(":VaiTro", $this->VaiTro);

        return $stmt->execute();
    }

    // Xóa tác giả giảng viên
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaBaiBao=:MaBaiBao AND MaGV=:MaGV";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":MaGV", $this->MaGV);

        return $stmt->execute();
    }
}
?>
