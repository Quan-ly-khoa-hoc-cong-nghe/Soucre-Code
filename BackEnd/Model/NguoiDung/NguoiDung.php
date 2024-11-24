<?php
class NguoiDung {
    private $conn;
    private $table = "NguoiDung";

    public $MaNguoiDung;
    public $VaiTro;
    public $MatKhau;
    public $MaNhanVien;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra xem người dùng đã tồn tại hay chưa
    private function isUserExist() {
        $query = "SELECT MaNguoiDung FROM " . $this->table . " WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Kiểm tra xem mã nhân viên có tồn tại trong bảng NhanVien
    private function isNhanVienExist() {
        $query = "SELECT MaNhanVien FROM NhanVien WHERE MaNhanVien = :MaNhanVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Thêm người dùng mới
    public function add() {
        // Kiểm tra mã nhân viên hợp lệ
        if (!$this->isNhanVienExist()) {
            echo json_encode(["message" => "Mã nhân viên không tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra người dùng đã tồn tại chưa
        if ($this->isUserExist()) {
            echo json_encode(["message" => "Người dùng đã tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (MaNguoiDung, VaiTro, MatKhau, MaNhanVien) 
                  VALUES (:MaNguoiDung, :VaiTro, :MatKhau, :MaNhanVien)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":MatKhau", $this->MatKhau); // Mật khẩu nên mã hóa
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật người dùng
    public function update() {
        // Kiểm tra mã nhân viên hợp lệ
        if (!$this->isNhanVienExist()) {
            echo json_encode(["message" => "Mã nhân viên không tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra người dùng có tồn tại để cập nhật
        if (!$this->isUserExist()) {
            echo json_encode(["message" => "Người dùng không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                  SET VaiTro = :VaiTro, MatKhau = :MatKhau, MaNhanVien = :MaNhanVien 
                  WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":MatKhau", $this->MatKhau);
        $stmt->bindParam(":MaNhanVien", $this->MaNhanVien);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa người dùng
    public function delete() {
        // Kiểm tra người dùng có tồn tại để xóa
        if (!$this->isUserExist()) {
            echo json_encode(["message" => "Người dùng không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "DELETE FROM " . $this->table . " WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy tất cả người dùng
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy người dùng theo ID
    public function getById() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->execute();
        return $stmt;
    }
}
?>
