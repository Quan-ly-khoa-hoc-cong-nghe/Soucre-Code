<?php
class DuAnNCNT {
    private $conn;
    private $table = "DuAnNCNT";

    public $ma_du_an;
    public $ten_du_an;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $file_hop_dong;
    public $trang_thai;
    public $ma_ho_so;
    public $ma_dat_hang;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả dự án
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một dự án
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_du_an = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_du_an);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm dự án
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_du_an=?, ten_du_an=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=?, ma_dat_hang=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_du_an);
        $stmt->bindParam(2, $this->ten_du_an);
        $stmt->bindParam(3, $this->ngay_bat_dau);
        $stmt->bindParam(4, $this->ngay_ket_thuc);
        $stmt->bindParam(5, $this->file_hop_dong);
        $stmt->bindParam(6, $this->trang_thai);
        $stmt->bindParam(7, $this->ma_ho_so);
        $stmt->bindParam(8, $this->ma_dat_hang);
        return $stmt->execute();
    }

    // Cập nhật dự án
    public function update() {
        $query = "UPDATE " . $this->table . " SET ten_du_an=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=?, ma_dat_hang=? WHERE ma_du_an=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ten_du_an);
        $stmt->bindParam(2, $this->ngay_bat_dau);
        $stmt->bindParam(3, $this->ngay_ket_thuc);
        $stmt->bindParam(4, $this->file_hop_dong);
        $stmt->bindParam(5, $this->trang_thai);
        $stmt->bindParam(6, $this->ma_ho_so);
        $stmt->bindParam(7, $this->ma_dat_hang);
        $stmt->bindParam(8, $this->ma_du_an);
        return $stmt->execute();
    }

    // Xóa dự án
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_du_an = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_du_an);
        return $stmt->execute();
    }
}
?>
