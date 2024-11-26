<?php
class ThamDinhBaiBao {
    private $conn;
    private $table_name = "ThamDinhBaiBao";

    public $MaThamDinh;
    public $NgayThamDinh;
    public $DanhGiaBaiBao;
    public $KetQua;
    public $NhanXet;
    public $MaBaiBao;  // Thêm trường MaBaiBao

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo mã thẩm định tự động
    private function generateMaThamDinh() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã thẩm định mới theo định dạng TDBBKH + (count + 1)
        return "TDBBKH" . ($count + 1);
    }

    // Thêm thông tin thẩm định bài báo
    public function add() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->NgayThamDinh) || empty($this->DanhGiaBaiBao) || empty($this->KetQua) || empty($this->NhanXet) || empty($this->MaBaiBao)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Tạo mã thẩm định tự động
        $this->MaThamDinh = $this->generateMaThamDinh();

        $query = "INSERT INTO " . $this->table_name . " SET MaThamDinh=:MaThamDinh, NgayThamDinh=:NgayThamDinh, DanhGiaBaiBao=:DanhGiaBaiBao, KetQua=:KetQua, NhanXet=:NhanXet, MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);
        $stmt->bindParam(":NgayThamDinh", $this->NgayThamDinh);
        $stmt->bindParam(":DanhGiaBaiBao", $this->DanhGiaBaiBao);
        $stmt->bindParam(":KetQua", $this->KetQua);
        $stmt->bindParam(":NhanXet", $this->NhanXet);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

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
        $query = "UPDATE " . $this->table_name . " SET NgayThamDinh=:NgayThamDinh, DanhGiaBaiBao=:DanhGiaBaiBao, KetQua=:KetQua, NhanXet=:NhanXet, MaBaiBao=:MaBaiBao WHERE MaThamDinh=:MaThamDinh";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaThamDinh", $this->MaThamDinh);
        $stmt->bindParam(":NgayThamDinh", $this->NgayThamDinh);
        $stmt->bindParam(":DanhGiaBaiBao", $this->DanhGiaBaiBao);
        $stmt->bindParam(":KetQua", $this->KetQua);
        $stmt->bindParam(":NhanXet", $this->NhanXet);
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);

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
