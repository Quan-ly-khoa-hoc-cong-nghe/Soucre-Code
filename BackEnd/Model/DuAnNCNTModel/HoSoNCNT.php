<?php
class HoSoNCNT {
    private $conn;
    private $table = "HoSoNCNT";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;
    public $MaDatHang;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy giá trị MaHoSo mới
    private function generateMaHoSo() {
        // Lấy phần số tự động từ các bản ghi có sẵn trong bảng
        $query = "SELECT MAX(CAST(SUBSTRING(MaHoSo, 7) AS UNSIGNED)) AS max_id FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Tạo MaHoSo mới
        $newId = (int)$result['max_id'] + 1;
        return 'HSNCNT' . $newId;
    }

    // Thêm hồ sơ mới
    public function add() {
        // Tạo MaHoSo mới
        $this->MaHoSo = $this->generateMaHoSo();

        $query = "INSERT INTO " . $this->table . " (MaHoSo, NgayNop, FileHoSo, TrangThai, MaDatHang, MaKhoa) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->bindParam(2, $this->NgayNop);
        $stmt->bindParam(3, $this->FileHoSo);
        $stmt->bindParam(4, $this->TrangThai);
        $stmt->bindParam(5, $this->MaDatHang);
        $stmt->bindParam(6, $this->MaKhoa);

        return $stmt->execute();
    }

    // Cập nhật hồ sơ
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET NgayNop = ?, FileHoSo = ?, TrangThai = ?, MaDatHang = ?, MaKhoa = ? 
                  WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayNop);
        $stmt->bindParam(2, $this->FileHoSo);
        $stmt->bindParam(3, $this->TrangThai);
        $stmt->bindParam(4, $this->MaDatHang);
        $stmt->bindParam(5, $this->MaKhoa);
        $stmt->bindParam(6, $this->MaHoSo);

        return $stmt->execute();
    }

    // Xóa hồ sơ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);

        return $stmt->execute();
    }
}
