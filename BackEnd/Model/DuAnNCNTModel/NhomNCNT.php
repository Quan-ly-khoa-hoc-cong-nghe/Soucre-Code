<?php
class NhomNCNT {
    private $conn;
    private $table = "NhomNCNT";

    public $ma_ho_so;
    public $ma_gv;

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
        $query = "SELECT * FROM " . $this->table . " WHERE ma_ho_so = ? AND ma_gv = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->bindParam(2, $this->ma_gv);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm thành viên vào nhóm
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_ho_so=?, ma_gv=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->bindParam(2, $this->ma_gv);
        return $stmt->execute();
    }

    // Xóa thành viên khỏi nhóm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_ho_so = ? AND ma_gv = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->bindParam(2, $this->ma_gv);
        return $stmt->execute();
    }
}
?>
