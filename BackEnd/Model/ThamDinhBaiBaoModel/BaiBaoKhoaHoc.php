<?php
class BaiBaoKhoaHoc {
    private $conn;
    private $table_name = "BaiBaoKhoaHoc";

    public $MaBaiBao;
    public $TenBaiBao;
    public $urlBaiBao;
    public $NgayXuatBan;
    public $MaThamDinh;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm bài báo khoa học
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaBaiBao=:MaBaiBao, TenBaiBao=:TenBaiBao, urlBaiBao=:urlBaiBao, NgayXuatBan=:NgayXuatBan, MaThamDinh=:MaThamDinh";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":TenBaiBao", $this->TenBaiBao);
        $stmt->bindParam(":urlBaiBao", $this->urlBaiBao);
        $stmt->bindParam(":NgayXuatBan", $this->NgayXuatBan);
        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);

        return $stmt->execute();
    }

    // Lấy tất cả bài báo khoa học
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật bài báo khoa học
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenBaiBao=:TenBaiBao, urlBaiBao=:urlBaiBao, NgayXuatBan=:NgayXuatBan, MaThamDinh=:MaThamDinh WHERE MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":TenBaiBao", $this->TenBaiBao);
        $stmt->bindParam(":urlBaiBao", $this->urlBaiBao);
        $stmt->bindParam(":NgayXuatBan", $this->NgayXuatBan);
        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);

        return $stmt->execute();
    }

    // Xóa bài báo khoa học
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }
}
?>
