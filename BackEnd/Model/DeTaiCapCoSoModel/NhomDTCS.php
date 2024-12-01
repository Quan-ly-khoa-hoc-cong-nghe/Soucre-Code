<?php
class NhomDTCS {
    private $conn;
    private $table = "NhomDTCS";

    public $MaDTCS;
    public $MaGV;
    public $VaiTro;  // Thêm VaiTro vào model

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
        $query = "SELECT * FROM " . $this->table . " WHERE MaDTCS = ? AND MaGV = ?";  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->bindParam(2, $this->MaGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm thành viên vào nhóm
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaDTCS = ?, MaGV = ?, VaiTro = ?";  // Thêm VaiTro vào câu lệnh
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->bindParam(2, $this->MaGV);
        $stmt->bindParam(3, $this->VaiTro);  // Gắn VaiTro vào câu lệnh
        return $stmt->execute();
    }

    // Xóa thành viên khỏi nhóm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDTCS = ? AND MaGV = ?";  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);
        $stmt->bindParam(2, $this->MaGV);
        return $stmt->execute();
    }
}
?>
