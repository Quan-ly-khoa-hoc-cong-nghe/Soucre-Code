<?php
class GiangVienNCKHGV {
    private $conn;
    private $table_name = "GiangVienNCKHGV";

    public $SoGioQuyDoi;
    public $MaNhomNCKHGV;
    public $VaiTro;  // Thêm VaiTro vào model
    public $MaGV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả dữ liệu
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy dữ liệu của một giảng viên
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaGV = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới dữ liệu
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET SoGioQuyDoi=:SoGioQuyDoi, MaNhomNCKHGV=:MaNhomNCKHGV, VaiTro=:VaiTro, MaGV=:MaGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":SoGioQuyDoi", $this->SoGioQuyDoi);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":VaiTro", $this->VaiTro);  // Thêm VaiTro vào bind
        $stmt->bindParam(":MaGV", $this->MaGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật dữ liệu
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET SoGioQuyDoi=:SoGioQuyDoi, MaNhomNCKHGV=:MaNhomNCKHGV, VaiTro=:VaiTro WHERE MaGV=:MaGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":SoGioQuyDoi", $this->SoGioQuyDoi);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":VaiTro", $this->VaiTro);  // Thêm VaiTro vào bind
        $stmt->bindParam(":MaGV", $this->MaGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa dữ liệu
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
