<?php
class NhomDTCS {
    private $conn;
    private $table = "NhomDTCS";

    public $MaDTCS;  // Cập nhật theo tên trường trong CSDL
    public $VaiTro;  // Cập nhật theo tên trường trong CSDL
    public $MaGV;    // Cập nhật theo tên trường trong CSDL

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
        $query = "SELECT * FROM " . $this->table . " WHERE MaDTCS = ? AND MaGV = ?";  // Cập nhật tên trường
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->VaiTro);  // Cập nhật tên thuộc tính
        $stmt->bindParam(3, $this->MaGV);    // Cập nhật tên thuộc tính
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm thành viên vào nhóm
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaDTCS=?, VaiTro=?, MaGV=?";  // Cập nhật tên trường
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->VaiTro);  // Cập nhật tên thuộc tính
        $stmt->bindParam(3, $this->MaGV);    // Cập nhật tên thuộc tính
        return $stmt->execute();
    }

    // Xóa thành viên khỏi nhóm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDTCS = ? AND MaGV = ?";  // Cập nhật tên trường
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDTCS);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->VaiTro);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->MaGV);    // Cập nhật tên thuộc tính
        return $stmt->execute();
    }
}
?>
