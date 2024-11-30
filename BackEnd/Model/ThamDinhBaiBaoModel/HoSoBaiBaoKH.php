<?php
class HoSoBaiBaoKH {
    private $conn;
    private $table_name = "HoSoBaiBaoKH";

    public $MaHoSo;
    public $TrangThai;
    public $NgayNop;
    public $fileHoSo;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo mã hồ sơ tự động
    private function generateMaHoSo() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã hồ sơ mới theo định dạng HSBBKH + (count + 1)
        return "HSBBKH" . ($count + 1);
    }

    // Thêm hồ sơ bài báo
    public function add() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->TrangThai) || empty($this->NgayNop) || empty($this->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Tạo mã hồ sơ tự động
        $this->MaHoSo = $this->generateMaHoSo();

        $query = "INSERT INTO " . $this->table_name . " SET MaHoSo=:MaHoSo, TrangThai=:TrangThai, NgayNop=:NgayNop, fileHoSo:= fileHoSo, MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam("fileHoSo", $this->fileHoSo);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        return $stmt->execute();
    }

    // Lấy tất cả hồ sơ bài báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật hồ sơ bài báo
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TrangThai=:TrangThai, NgayNop=:NgayNop, fileHoSo:=fileHoSo, MaKhoa=:MaKhoa WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        $stmt->bindParam("fileHoSo", $this->fileHoSo);

        return $stmt->execute();
    }

    // Xóa hồ sơ bài báo
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);

        return $stmt->execute();
    }
}
?>
