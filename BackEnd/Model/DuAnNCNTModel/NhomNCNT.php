<?php
class NhomNCNT {
    private $conn;
    private $table = "NhomNCNT";

    public $MaDuAn;
    public $MaGV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các thành viên nhóm
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một thành viên nhóm
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDuAn = ? AND MaGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->bindParam(2, $this->MaGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm thành viên vào nhóm
    public function add() {
        $query = "INSERT INTO " . $this->table . " (MaDuAn, MaGV) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->bindParam(2, $this->MaGV);
        return $stmt->execute();
    }

    // Cập nhật mã giảng viên trong nhóm
    public function update($MaGVMoi) {
        $query = "UPDATE " . $this->table . " SET MaGV=? WHERE MaDuAn=? AND MaGV=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaGVMoi);
        $stmt->bindParam(2, $this->MaDuAn);
        $stmt->bindParam(3, $this->MaGV);
        return $stmt->execute();
    }

    // Xóa thành viên khỏi nhóm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDuAn = ? AND MaGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDuAn);
        $stmt->bindParam(2, $this->MaGV);
        return $stmt->execute();
    }
}
?>
