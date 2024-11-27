<?php
class HoSoNCKHGV {
    private $conn;
    private $table_name = "HoSoNCKHGV";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một hồ sơ
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaHoSo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mã hồ sơ tự động
    private function generateMaHoSo() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã hồ sơ mới theo định dạng HSNCGV + (count + 1)
        return "HSNCGV" . ($count + 1);
    }

    // Tạo mới hồ sơ
    public function create() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->NgayNop) || empty($this->FileHoSo) || empty($this->TrangThai) || empty($this->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Tạo mã hồ sơ tự động
        $this->MaHoSo = $this->generateMaHoSo();

        $query = "INSERT INTO " . $this->table_name . " SET MaHoSo=:MaHoSo, NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai, MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":FileHoSo", $this->FileHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật hồ sơ
    public function update() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->MaHoSo) || empty($this->NgayNop) || empty($this->FileHoSo) || empty($this->TrangThai)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra sự tồn tại của hồ sơ trước khi cập nhật
        if (!$this->isHoSoExist()) {
            echo json_encode(["message" => "Hồ sơ không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);

        // Binding tham số
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":FileHoSo", $this->FileHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa hồ sơ
    public function delete() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->MaHoSo)) {
            echo json_encode(["message" => "Mã hồ sơ không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra sự tồn tại của hồ sơ trước khi xóa
        if (!$this->isHoSoExist()) {
            echo json_encode(["message" => "Hồ sơ không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Kiểm tra sự tồn tại của hồ sơ
    private function isHoSoExist() {
        $query = "SELECT MaHoSo FROM " . $this->table_name . " WHERE MaHoSo = :MaHoSo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }
}
?>
