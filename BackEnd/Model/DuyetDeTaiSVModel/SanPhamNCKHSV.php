<?php
class SanPhamNCKHSV {
    private $conn;
    private $table_name = "SanPhamNCKHSV";

    public $TenSanPham;
    public $NgayHoanThanh;
    public $KetQua;
    public $MaDeTaiSV;
    public $FileSanPham; // Thêm thuộc tính mới

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đọc tất cả sản phẩm
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY NgayHoanThanh DESC";
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
            $sql = "INSERT INTO " . $this->table_name . " (TenSanPham, NgayHoanThanh, KetQua, MaDeTaiSV, FileSanPham) 
                    VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :maDeTaiSV, :fileSanPham)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':fileSanPham', $this->FileSanPham);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật sản phẩm
    public function update() {
        try {
            $fileSanPham = !empty($this->FileSanPham) ? $this->FileSanPham : null;
    
            $sql = "UPDATE " . $this->table_name . "
                    SET TenSanPham = :tenSanPham, NgayHoanThanh = :ngayHoanThanh, KetQua = :ketQua, FileSanPham = :fileSanPham
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
    
            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':fileSanPham', $fileSanPham);  // Lưu đường dẫn file vào CSDL
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
    
            return $stmt->execute();  // Chạy câu lệnh SQL
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];  // Bắt lỗi và trả về thông báo lỗi
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
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
