<?php
class HoSoNCKHGV {
    private $conn;
    private $table_name = "HoSoNCKHGV";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo;
    public $TrangThai;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một hồ sơ
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaHoSo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới hồ sơ
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaHoSo=:MaHoSo, NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai";
        $stmt = $this->conn->prepare($query);

        // Xử lý dữ liệu
        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":FileHoSo", $this->FileHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật hồ sơ
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai WHERE MaHoSo=:MaHoSo";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoSo", $this->MaHoSo);
        $stmt->bindParam(":NgayNop", $this->NgayNop);
        $stmt->bindParam(":FileHoSo", $this->FileHoSo);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa hồ sơ
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaHoSo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
