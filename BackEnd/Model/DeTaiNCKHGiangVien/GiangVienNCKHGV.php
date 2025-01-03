<?php
class GiangVienNCKHGV {
    private $conn;
    private $table_name = "GiangVienNCKHGV";

    public $SoGioQuyDoi;
    public $MaNhomNCKHGV;
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
        $query = "INSERT INTO " . $this->table_name . " SET SoGioQuyDoi=:SoGioQuyDoi, MaNhomNCKHGV=:MaNhomNCKHGV, MaGV=:MaGV";
        $stmt = $this->conn->prepare($query);
    
        // Kiểm tra nếu SoGioQuyDoi là rỗng, gán giá trị NULL
        if (empty($this->SoGioQuyDoi)) {
            $this->SoGioQuyDoi = NULL;
        }
    
        // Gắn dữ liệu
        $stmt->bindParam(":SoGioQuyDoi", $this->SoGioQuyDoi);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaGV", $this->MaGV);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật dữ liệu
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET SoGioQuyDoi=:SoGioQuyDoi, MaNhomNCKHGV=:MaNhomNCKHGV WHERE MaGV=:MaGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":SoGioQuyDoi", $this->SoGioQuyDoi);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaGV", $this->MaGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa dữ liệu
   // Xóa dữ liệu theo MaGV và MaNhomNCKHGV
public function delete() {
    $query = "DELETE FROM " . $this->table_name . " WHERE MaGV = ? AND MaNhomNCKHGV = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->MaGV);
    $stmt->bindParam(2, $this->MaNhomNCKHGV);

    if ($stmt->execute()) {
        return true;
    }
    return false;
}

}
?>
