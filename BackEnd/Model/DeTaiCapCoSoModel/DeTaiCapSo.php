<?php
class DeTaiCapSo {
    private $conn;
    private $table = "DeTaiCapSo";

    public $ma_dtcs;
    public $ten_de_tai;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $file_hop_dong;
    public $trang_thai;
    public $ma_ho_so;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đề tài
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một đề tài
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đề tài
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_dtcs=?, ten_de_tai=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
        $stmt->bindParam(2, $this->ten_de_tai);
        $stmt->bindParam(3, $this->ngay_bat_dau);
        $stmt->bindParam(4, $this->ngay_ket_thuc);
        $stmt->bindParam(5, $this->file_hop_dong);
        $stmt->bindParam(6, $this->trang_thai);
        $stmt->bindParam(7, $this->ma_ho_so);
        return $stmt->execute();
    }

    // Cập nhật đề tài
    public function update() {
        $query = "UPDATE " . $this->table . " SET ten_de_tai=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=? WHERE ma_dtcs=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ten_de_tai);
        $stmt->bindParam(2, $this->ngay_bat_dau);
        $stmt->bindParam(3, $this->ngay_ket_thuc);
        $stmt->bindParam(4, $this->file_hop_dong);
        $stmt->bindParam(5, $this->trang_thai);
        $stmt->bindParam(6, $this->ma_ho_so);
        $stmt->bindParam(7, $this->ma_dtcs);
        return $stmt->execute();
    }

    // Xóa đề tài
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
        return $stmt->execute();
    }
}
?>
