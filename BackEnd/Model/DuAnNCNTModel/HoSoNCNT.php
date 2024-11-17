<?php
class HoSoNCNT {
    private $conn;
    private $table = "HoSoNCNT";

    public $ma_ho_so;
    public $ngay_nop;
    public $file_ho_so;
    public $trang_thai;
    public $ma_dat_hang;
    public $ma_khoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một hồ sơ
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_ho_so = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm hồ sơ
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_ho_so=?, ngay_nop=?, file_ho_so=?, trang_thai=?, ma_dat_hang=?, ma_khoa=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->bindParam(2, $this->ngay_nop);
        $stmt->bindParam(3, $this->file_ho_so);
        $stmt->bindParam(4, $this->trang_thai);
        $stmt->bindParam(5, $this->ma_dat_hang);
        $stmt->bindParam(6, $this->ma_khoa);
        return $stmt->execute();
    }

    // Cập nhật hồ sơ
    public function update() {
        $query = "UPDATE " . $this->table . " SET ngay_nop=?, file_ho_so=?, trang_thai=?, ma_dat_hang=?, ma_khoa=? WHERE ma_ho_so=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_nop);
        $stmt->bindParam(2, $this->file_ho_so);
        $stmt->bindParam(3, $this->trang_thai);
        $stmt->bindParam(4, $this->ma_dat_hang);
        $stmt->bindParam(5, $this->ma_khoa);
        $stmt->bindParam(6, $this->ma_ho_so);
        return $stmt->execute();
    }

    // Xóa hồ sơ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_ho_so = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        return $stmt->execute();
    }
}
?>
