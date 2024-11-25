<?php

class KeHoachHoiThao {
    private $conn;
    private $table = "KeHoachHoiThao";

    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $kinh_phi;
    public $file_ke_hoach;
    public $ma_hoi_thao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một kế hoạch theo mã
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaHoiThao = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_hoi_thao);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm kế hoạch mới
    public function add() {
        try {
            $query = "INSERT INTO " . $this->table . " (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaHoiThao) 
                      VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maHoiThao)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':ngayBatDau', $this->ngay_bat_dau);
            $stmt->bindParam(':ngayKetThuc', $this->ngay_ket_thuc);
            $stmt->bindParam(':kinhPhi', $this->kinh_phi);
            $stmt->bindParam(':fileKeHoach', $this->file_ke_hoach);
            $stmt->bindParam(':maHoiThao', $this->ma_hoi_thao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm kế hoạch: " . $e->getMessage());
        }
    }

    // Cập nhật kế hoạch
    public function update() {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET NgayBatDau = :ngayBatDau, NgayKetThuc = :ngayKetThuc, KinhPhi = :kinhPhi, FileKeHoach = :fileKeHoach 
                      WHERE MaHoiThao = :maHoiThao";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':ngayBatDau', $this->ngay_bat_dau);
            $stmt->bindParam(':ngayKetThuc', $this->ngay_ket_thuc);
            $stmt->bindParam(':kinhPhi', $this->kinh_phi);
            $stmt->bindParam(':fileKeHoach', $this->file_ke_hoach);
            $stmt->bindParam(':maHoiThao', $this->ma_hoi_thao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật kế hoạch: " . $e->getMessage());
        }
    }

    // Xóa kế hoạch
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE MaHoiThao = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_hoi_thao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa kế hoạch: " . $e->getMessage());
        }
    }
}
?>
