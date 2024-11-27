<?php
class DeTaiCapSo {
    private $conn;
    private $table = "DeTaiCapSo";

    public $MaDTCS;
    public $TenDeTai;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $FileHopDong;
    public $TrangThai;
    public $MaHoSo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đề tài cấp sở
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một đề tài cấp sở
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mã MaDTCS tự động theo định dạng DTCS + (count + 1)
    private function generateMaDTCS() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã đề tài mới theo định dạng DTCS + (count + 1)
        return "DTCS" . ($count + 1);
    }

    // Thêm đề tài cấp sở
    public function add() {
        // Tạo mã MaDTCS tự động
        $this->MaDTCS = $this->generateMaDTCS();

        // Thực hiện thêm mới đề tài
        $query = "INSERT INTO " . $this->table . " (MaDTCS, TenDeTai, NgayBatDau, NgayKetThuc, FileHopDong, TrangThai, MaHoSo) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->bindParam(2, $this->TenDeTai);
        $stmt->bindParam(3, $this->NgayBatDau);
        $stmt->bindParam(4, $this->NgayKetThuc);
        $stmt->bindParam(5, $this->FileHopDong);
        $stmt->bindParam(6, $this->TrangThai);
        $stmt->bindParam(7, $this->MaHoSo);

        return $stmt->execute();
    }

    // Cập nhật đề tài cấp sở
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET TenDeTai = ?, NgayBatDau = ?, NgayKetThuc = ?, FileHopDong = ?, TrangThai = ?, MaHoSo = ? 
                  WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->TenDeTai);
        $stmt->bindParam(2, $this->NgayBatDau);
        $stmt->bindParam(3, $this->NgayKetThuc);
        $stmt->bindParam(4, $this->FileHopDong);
        $stmt->bindParam(5, $this->TrangThai);
        $stmt->bindParam(6, $this->MaHoSo);
        $stmt->bindParam(7, $this->MaDTCS);

        return $stmt->execute();
    }

    // Xóa đề tài cấp sở
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDTCS = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);

        return $stmt->execute();
    }
}
?>
