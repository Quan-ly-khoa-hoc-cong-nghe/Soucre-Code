<?php
class ThamDinhBaiBao {
    private $conn;
    private $table_name = "ThamDinhBaiBao";

    public $MaThamDinh;
    public $NgayThamDinh;
    public $DanhGiaBaiBao;
    public $KetQua;
    public $NhanXet;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm thông tin thẩm định bài báo
    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET MaThamDinh=:MaThamDinh, NgayThamDinh=:NgayThamDinh, DanhGiaBaiBao=:DanhGiaBaiBao, KetQua=:KetQua, NhanXet=:NhanXet";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);
        $stmt->bindParam(":NgayThamDinh", $this->NgayThamDinh);
        $stmt->bindParam(":DanhGiaBaiBao", $this->DanhGiaBaiBao);
        $stmt->bindParam(":KetQua", $this->KetQua);
        $stmt->bindParam(":NhanXet", $this->NhanXet);

        return $stmt->execute();
    }

    // Lấy tất cả thông tin thẩm định bài báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật thông tin thẩm định bài báo
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET NgayThamDinh=:NgayThamDinh, DanhGiaBaiBao=:DanhGiaBaiBao, KetQua=:KetQua, NhanXet=:NhanXet WHERE MaThamDinh=:MaThamDinh";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);
        $stmt->bindParam(":NgayThamDinh", $this->NgayThamDinh);
        $stmt->bindParam(":DanhGiaBaiBao", $this->DanhGiaBaiBao);
        $stmt->bindParam(":KetQua", $this->KetQua);
        $stmt->bindParam(":NhanXet", $this->NhanXet);

        return $stmt->execute();
    }

    // Xóa thông tin thẩm định bài báo
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaThamDinh=:MaThamDinh";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);

        return $stmt->execute();
    }
}
?>
