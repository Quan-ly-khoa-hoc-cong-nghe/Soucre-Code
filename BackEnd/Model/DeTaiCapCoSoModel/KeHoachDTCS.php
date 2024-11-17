<?php
class KeHoachDTCS {
    private $conn;
    private $table = "KeHoachDTCS";

    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $kinh_phi;
    public $file_ke_hoach;
    public $ma_dtcs;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một kế hoạch
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm kế hoạch
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ngay_bat_dau=?, ngay_ket_thuc=?, kinh_phi=?, file_ke_hoach=?, ma_dtcs=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_bat_dau);
        $stmt->bindParam(2, $this->ngay_ket_thuc);
        $stmt->bindParam(3, $this->kinh_phi);
        $stmt->bindParam(4, $this->file_ke_hoach);
        $stmt->bindParam(5, $this->ma_dtcs);
        return $stmt->execute();
    }

    // Cập nhật kế hoạch
    public function update() {
        $query = "UPDATE " . $this->table . " SET ngay_bat_dau=?, ngay_ket_thuc=?, kinh_phi=?, file_ke_hoach=? WHERE ma_dtcs=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_bat_dau);
        $stmt->bindParam(2, $this->ngay_ket_thuc);
        $stmt->bindParam(3, $this->kinh_phi);
        $stmt->bindParam(4, $this->file_ke_hoach);
        $stmt->bindParam(5, $this->ma_dtcs);
        return $stmt->execute();
    }

    // Xóa kế hoạch
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
        return $stmt->execute();
    }
}
?>
