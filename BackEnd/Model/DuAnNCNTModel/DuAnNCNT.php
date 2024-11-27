<?php
class DuAnNCNT {
    private $conn;
    private $table = "DuAnNCNT";

    public $MaDuAn;
    public $TenDuAn;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $FileHopDong;
    public $TrangThai;
    public $MaHoSo;
    public $MaDatHang;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy số tự động tăng cuối cùng trong MaDuAn
    private function generateMaDuAn() {
        // Truy vấn để lấy MaDuAn lớn nhất
        $query = "SELECT MAX(CAST(SUBSTRING(MaDuAn, 6) AS UNSIGNED)) AS last_id FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu không có bản ghi nào, bắt đầu từ 1
        $last_id = isset($result['last_id']) ? $result['last_id'] : 0;

        // Tạo MaDuAn mới có dạng NCNT+X
        return 'NCNT' . str_pad($last_id + 1, 3, '0', STR_PAD_LEFT);
    }

    // Lấy tất cả dự án
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một dự án
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm dự án
    public function add() {
        // Tạo MaDuAn mới
        $this->MaDuAn = $this->generateMaDuAn();

        $query = "INSERT INTO " . $this->table . " (MaDuAn, TenDuAn, NgayBatDau, NgayKetThuc, FileHopDong, TrangThai, MaHoSo, MaDatHang) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->bindParam(2, $this->TenDuAn);
        $stmt->bindParam(3, $this->NgayBatDau);
        $stmt->bindParam(4, $this->NgayKetThuc);
        $stmt->bindParam(5, $this->FileHopDong);
        $stmt->bindParam(6, $this->TrangThai);
        $stmt->bindParam(7, $this->MaHoSo);
        $stmt->bindParam(8, $this->MaDatHang);

        return $stmt->execute();
    }

    // Cập nhật dự án
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET TenDuAn = ?, NgayBatDau = ?, NgayKetThuc = ?, FileHopDong = ?, TrangThai = ?, MaHoSo = ?, MaDatHang = ? 
                  WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->TenDuAn);
        $stmt->bindParam(2, $this->NgayBatDau);
        $stmt->bindParam(3, $this->NgayKetThuc);
        $stmt->bindParam(4, $this->FileHopDong);
        $stmt->bindParam(5, $this->TrangThai);
        $stmt->bindParam(6, $this->MaHoSo);
        $stmt->bindParam(7, $this->MaDatHang);
        $stmt->bindParam(8, $this->MaDuAn);

        return $stmt->execute();
    }

    // Xóa dự án
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDuAn = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);

        return $stmt->execute();
    }
}
