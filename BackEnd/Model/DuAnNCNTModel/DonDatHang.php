<?php
class DonDatHang {
    private $conn;
    private $table = "DonDatHang";

    public $ma_dat_hang;
    public $ngay_dat;
    public $file_dat_hang;
    public $ma_doi_tac;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đơn đặt hàng
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một đơn đặt hàng
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dat_hang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dat_hang);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đơn đặt hàng
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_dat_hang=?, ngay_dat=?, file_dat_hang=?, ma_doi_tac=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dat_hang);
        $stmt->bindParam(2, $this->ngay_dat);
        $stmt->bindParam(3, $this->file_dat_hang);
        $stmt->bindParam(4, $this->ma_doi_tac);
        return $stmt->execute();
    }

    // Cập nhật đơn đặt hàng
    public function update() {
        $query = "UPDATE " . $this->table . " SET ngay_dat=?, file_dat_hang=?, ma_doi_tac=? WHERE ma_dat_hang=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_dat);
        $stmt->bindParam(2, $this->file_dat_hang);
        $stmt->bindParam(3, $this->ma_doi_tac);
        $stmt->bindParam(4, $this->ma_dat_hang);
        return $stmt->execute();
    }

    // Xóa đơn đặt hàng
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_dat_hang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dat_hang);
        return $stmt->execute();
    }
}
?>
