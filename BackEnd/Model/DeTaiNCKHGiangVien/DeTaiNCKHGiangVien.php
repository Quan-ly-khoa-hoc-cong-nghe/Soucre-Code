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

    // Tạo mới đề tài NCKH
    public function create() {
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
