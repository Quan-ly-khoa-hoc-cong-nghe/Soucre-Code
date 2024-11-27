<?php
class BaiBaoKhoaHoc {
    private $conn;
    private $table_name = "BaiBaoKhoaHoc";

    public $MaBaiBao;
    public $TenBaiBao;
    public $urlBaiBao;
    public $NgayXuatBan;
    public $MaHoSo; // Đổi từ MaThamDinh thành MaHoSo

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo mã bài báo tự động
    private function generateMaBaiBao() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã bài báo mới theo định dạng BBKH + (count + 1)
        return "BBKH" . ($count + 1);
    }

    // Thêm bài báo khoa học
    public function add() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->TenBaiBao) || empty($this->urlBaiBao) || empty($this->NgayXuatBan) || empty($this->MaHoSo)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Tạo mã bài báo tự động
        $this->MaBaiBao = $this->generateMaBaiBao();

        $query = "INSERT INTO " . $this->table_name . " SET MaBaiBao=:MaBaiBao, TenBaiBao=:TenBaiBao, urlBaiBao=:urlBaiBao, NgayXuatBan=:NgayXuatBan, MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":TenBaiBao", $this->TenBaiBao);
        $stmt->bindParam(":urlBaiBao", $this->urlBaiBao);
        $stmt->bindParam(":NgayXuatBan", $this->NgayXuatBan);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo); // Cập nhật đây

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
        $query = "UPDATE " . $this->table_name . " SET TenBaiBao=:TenBaiBao, urlBaiBao=:urlBaiBao, NgayXuatBan=:NgayXuatBan, MaHoSo=:MaHoSo WHERE MaBaiBao=:MaBaiBao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaBaiBao", $this->MaBaiBao);
        $stmt->bindParam(":TenBaiBao", $this->TenBaiBao);
        $stmt->bindParam(":urlBaiBao", $this->urlBaiBao);
        $stmt->bindParam(":NgayXuatBan", $this->NgayXuatBan);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo); // Cập nhật đây

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
