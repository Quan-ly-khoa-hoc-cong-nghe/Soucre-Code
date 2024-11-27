<?php
class GiaiThuongBaiBao {
    private $conn;
    private $table_name = "GiaiThuongBaiBao";

    public $MaGiaiThuong;
    public $NgayKhenThuong;
    public $SoTienThuong;
    public $MaBaiBao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm giải thưởng bài báo
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET NgayKhenThuong=:NgayKhenThuong, SoTienThuong=:SoTienThuong, MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":NgayKhenThuong", $this->NgayKhenThuong);
        $stmt->bindParam(":SoTienThuong", $this->SoTienThuong);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }

    // Lấy tất cả giải thưởng bài báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật giải thưởng bài báo
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET SoTienThuong=:SoTienThuong WHERE NgayKhenThuong=:NgayKhenThuong AND MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":NgayKhenThuong", $this->NgayKhenThuong);
        $stmt->bindParam(":SoTienThuong", $this->SoTienThuong);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }

    // Xóa giải thưởng bài báo
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE NgayKhenThuong=:NgayKhenThuong AND MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":NgayKhenThuong", $this->NgayKhenThuong);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

        return $stmt->execute();
    }
}
?>
