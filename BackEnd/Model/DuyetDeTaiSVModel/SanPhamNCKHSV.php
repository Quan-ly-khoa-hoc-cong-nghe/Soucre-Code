<?php
class SanPhamNCKHSV {
    private $conn;
    private $table_name = "SanPhamNCKHSV";

    public $TenSanPham;
    public $NgayHoanThanh;
    public $KetQua;
    public $MaDeTaiSV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenSanPham ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm sản phẩm mới
    public function add() {
        try {
            if (empty($this->TenSanPham) || empty($this->NgayHoanThanh) || empty($this->KetQua) || empty($this->MaDeTaiSV)) {
                return false;
            }

            $sql = "INSERT INTO " . $this->table_name . " (TenSanPham, NgayHoanThanh, KetQua, MaDeTaiSV) 
                    VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :maDeTaiSV)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi thêm sản phẩm: " . $e->getMessage()];
        }
    }

    // Cập nhật sản phẩm
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET TenSanPham = :tenSanPham, NgayHoanThanh = :ngayHoanThanh, KetQua = :ketQua 
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi cập nhật sản phẩm: " . $e->getMessage()];
        }
    }

    // Xóa sản phẩm
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi xóa sản phẩm: " . $e->getMessage()];
        }
    }
}
?>
