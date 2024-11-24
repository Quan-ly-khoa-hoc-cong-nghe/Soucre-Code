<?php
class NhanVien {
    private $conn;
    private $table = "NhanVien";

    public $MaNhanVien;
    public $TenNhanVien;
    public $sdtNV;
    public $EmailNV;
    public $PhongCongTac;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra xem nhân viên đã tồn tại chưa
    private function isNhanVienExist() {
        $query = "SELECT MaNhanVien FROM " . $this->table . " WHERE MaNhanVien = :MaNhanVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Kiểm tra sự tồn tại của email nhân viên
    private function isEmailExist() {
        $query = "SELECT EmailNV FROM " . $this->table . " WHERE EmailNV = :EmailNV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":EmailNV", $this->EmailNV);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Thêm nhân viên mới
    public function add() {
        // Kiểm tra nếu nhân viên đã tồn tại
        if ($this->isNhanVienExist()) {
            echo json_encode(["message" => "Mã nhân viên đã tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra nếu email đã tồn tại
        if ($this->isEmailExist()) {
            echo json_encode(["message" => "Email đã tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (MaNhanVien, TenNhanVien, sdtNV, EmailNV, PhongCongTac) 
                  VALUES (:MaNhanVien, :TenNhanVien, :sdtNV, :EmailNV, :PhongCongTac)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);
        $stmt->bindParam(":TenNhanVien", $this->TenNhanVien);
        $stmt->bindParam(":sdtNV", $this->sdtNV);
        $stmt->bindParam(":EmailNV", $this->EmailNV);
        $stmt->bindParam(":PhongCongTac", $this->PhongCongTac);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin nhân viên
    public function update() {
        // Kiểm tra nếu nhân viên không tồn tại
        if (!$this->isNhanVienExist()) {
            echo json_encode(["message" => "Nhân viên không tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra nếu email đã tồn tại (trong trường hợp email thay đổi)
        if ($this->isEmailExist()) {
            echo json_encode(["message" => "Email đã tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                  SET TenNhanVien = :TenNhanVien, sdtNV = :sdtNV, EmailNV = :EmailNV, PhongCongTac = :PhongCongTac
                  WHERE MaNhanVien = :MaNhanVien";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);
        $stmt->bindParam(":TenNhanVien", $this->TenNhanVien);
        $stmt->bindParam(":sdtNV", $this->sdtNV);
        $stmt->bindParam(":EmailNV", $this->EmailNV);
        $stmt->bindParam(":PhongCongTac", $this->PhongCongTac);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa nhân viên
    public function delete() {
        // Kiểm tra nếu nhân viên không tồn tại
        if (!$this->isNhanVienExist()) {
            echo json_encode(["message" => "Nhân viên không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "DELETE FROM " . $this->table . " WHERE MaNhanVien = :MaNhanVien";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy tất cả nhân viên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy nhân viên theo ID
    public function getById() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaNhanVien = :MaNhanVien";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);
        $stmt->execute();
        return $stmt;
    }
}
?>
