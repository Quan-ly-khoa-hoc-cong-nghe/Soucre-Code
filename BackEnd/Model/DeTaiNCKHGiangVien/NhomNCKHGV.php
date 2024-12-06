<?php
class NhomNCKHGV {
    private $conn;
    private $table_name = "NhomNCKHGV";

    public $MaNhomNCKHGV;
    public $MaDeTaiNCKHGV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả nhóm NCKH
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private function generateMaNhomNCKHGV() {
    // Đếm số dòng hiện tại trong bảng NhomNCKHGV
    $query = "SELECT COUNT(*) FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    // Tạo mã nhóm mới theo định dạng MaNhomNCKHGV + (count + 1)
    return "MaNhomNCKHGV" . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // Ví dụ: MaNhomNCKHGV001, MaNhomNCKHGV002
}


    // Lấy một nhóm NCKH
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNhomNCKHGV = :MaNhomNCKHGV LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Tạo mới nhóm NCKH
    public function create() {
        // Nếu người dùng không cung cấp MaNhomNCKHGV, tạo mã nhóm tự động
        if (empty($this->MaNhomNCKHGV)) {
            $this->MaNhomNCKHGV = $this->generateMaNhomNCKHGV();
        }
    
        // Kiểm tra dữ liệu đầu vào
        if (empty($this->MaDeTaiNCKHGV)) {
            return false; // Nếu thiếu dữ liệu, trả về false
        }
    
        $query = "INSERT INTO " . $this->table_name . " (MaNhomNCKHGV, MaDeTaiNCKHGV) VALUES (:MaNhomNCKHGV, :MaDeTaiNCKHGV)";
        $stmt = $this->conn->prepare($query);
    
        // Gắn dữ liệu vào câu truy vấn
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);
    
        if ($stmt->execute()) {
            return true;
        }
    
        // Nếu có lỗi trong quá trình thực thi câu lệnh, trả về false
        return false;
    }
    

    // Cập nhật nhóm NCKH
    public function update() {
        // Kiểm tra dữ liệu đầu vào
        if (empty($this->MaNhomNCKHGV) || empty($this->MaDeTaiNCKHGV)) {
            return false; // Nếu thiếu dữ liệu, trả về false
        }

        $query = "UPDATE " . $this->table_name . " SET MaDeTaiNCKHGV = :MaDeTaiNCKHGV WHERE MaNhomNCKHGV = :MaNhomNCKHGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);

        if ($stmt->execute()) {
            return true;
        }

        // Thông báo lỗi chi tiết nếu có
        return false;
    }

    // Xóa nhóm NCKH
    public function delete() {
        // Kiểm tra dữ liệu đầu vào
        if (empty($this->MaNhomNCKHGV)) {
            return false; // Nếu thiếu mã nhóm, không thực hiện xóa
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHGV = :MaNhomNCKHGV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNhomNCKHGV", $this->MaNhomNCKHGV);

        if ($stmt->execute()) {
            return true;
        }

        // Thông báo lỗi chi tiết nếu có
        return false;
    }
}

?>
