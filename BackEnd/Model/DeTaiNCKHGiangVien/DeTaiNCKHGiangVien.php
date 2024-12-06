<?php
class DeTaiNCKHGV {
    private $conn;
    private $table_name = "DeTaiNCKHGV";

    public $MaDeTaiNCKHGV;
    public $TenDeTai;
    public $MoTa;
    public $FileHopDong;
    public $MaHoSo;
    public $MaLoaiHinhNCKH;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đề tài NCKH
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

    // Lấy một đề tài NCKH
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaDeTaiNCKHGV = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDeTaiNCKHGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra sự tồn tại của đề tài
    private function isDeTaiExist() {
        $query = "SELECT MaDeTaiNCKHGV FROM " . $this->table_name . " WHERE MaDeTaiNCKHGV = :MaDeTaiNCKHGV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Tạo mới mã đề tài tự động
   // Tạo mã đề tài tự động theo định dạng DTNCGV + (số dòng hiện tại + 1)
private function generateMaDeTaiNCKHGV() {
    // Đếm số dòng hiện tại trong bảng
    $query = "SELECT COUNT(*) FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    // Tạo mã đề tài mới theo định dạng DTNCGV + (count + 1)
    return "DTNCGV" . ($count + 1);
}


    // Tạo mới đề tài NCKH
    public function create() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->TenDeTai) || empty($this->FileHopDong) || empty($this->MaHoSo) || empty($this->MaLoaiHinhNCKH)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Tạo mã đề tài tự động
        $this->MaDeTaiNCKHGV = $this->generateMaDeTaiNCKHGV();

        $query = "INSERT INTO " . $this->table_name . " SET MaDeTaiNCKHGV=:MaDeTaiNCKHGV, TenDeTai=:TenDeTai, MoTa=:MoTa, FileHopDong=:FileHopDong, MaHoSo=:MaHoSo, MaLoaiHinhNCKH=:MaLoaiHinhNCKH";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);
        $stmt->bindParam(":TenDeTai", $this->TenDeTai);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":FileHopDong", $this->FileHopDong);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":MaLoaiHinhNCKH", $this->MaLoaiHinhNCKH);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật đề tài NCKH
    public function update() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->MaDeTaiNCKHGV) || empty($this->TenDeTai) || empty($this->FileHopDong) || empty($this->MaHoSo) || empty($this->MaLoaiHinhNCKH)) {
            echo json_encode(["message" => "Dữ liệu không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra sự tồn tại của đề tài trước khi cập nhật
        if (!$this->isDeTaiExist()) {
            echo json_encode(["message" => "Đề tài không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET TenDeTai=:TenDeTai, MoTa=:MoTa, FileHopDong=:FileHopDong, MaHoSo=:MaHoSo, MaLoaiHinhNCKH=:MaLoaiHinhNCKH WHERE MaDeTaiNCKHGV=:MaDeTaiNCKHGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":MaDeTaiNCKHGV", $this->MaDeTaiNCKHGV);
        $stmt->bindParam(":TenDeTai", $this->TenDeTai);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":FileHopDong", $this->FileHopDong);
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":MaLoaiHinhNCKH", $this->MaLoaiHinhNCKH);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa đề tài NCKH
    public function delete() {
        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($this->MaDeTaiNCKHGV)) {
            echo json_encode(["message" => "Mã đề tài không hợp lệ"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra sự tồn tại của đề tài trước khi xóa
        if (!$this->isDeTaiExist()) {
            echo json_encode(["message" => "Đề tài không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiNCKHGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDeTaiNCKHGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
