<?php
class HoSoDTCS {
    private $conn;
    private $table = "HoSoDTCS";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ đào tạo cơ sở
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một hồ sơ đào tạo cơ sở
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm hồ sơ đào tạo cơ sở
    public function add() {
        $query = "INSERT INTO " . $this->table . " (MaHoSo, NgayNop, FileHoSo, TrangThai, MaKhoa) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->bindParam(2, $this->NgayNop);
        $stmt->bindParam(3, $this->FileHoSo);
        $stmt->bindParam(4, $this->TrangThai);
        $stmt->bindParam(5, $this->MaKhoa);

        return $stmt->execute();
    }

    // Cập nhật hồ sơ đào tạo cơ sở
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET NgayNop = ?, FileHoSo = ?, TrangThai = ?, MaKhoa = ? 
                  WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayNop);
        $stmt->bindParam(2, $this->FileHoSo);
        $stmt->bindParam(3, $this->TrangThai);
        $stmt->bindParam(4, $this->MaKhoa);
        $stmt->bindParam(5, $this->MaHoSo);

        return $stmt->execute();
    }

    // Xóa hồ sơ đào tạo cơ sở
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);

        return $stmt->execute();
    }
}
?>
