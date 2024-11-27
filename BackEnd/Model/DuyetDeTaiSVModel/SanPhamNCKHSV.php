<?php
class SanPhamNCKHSV {
    private $conn;
    private $table_name = "SanPhamNCKHSV";

    public $MaSanPhamNCKHSV;
    public $TenSanPham;
    public $NgayHoanThanh;
    public $KetQua;
    public $FileSanPham;
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

            // Kiểm tra khóa ngoại MaDeTaiSV có hợp lệ không
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            $sql = "INSERT INTO " . $this->table_name . " (TenSanPham, NgayHoanThanh, KetQua, FileSanPham, MaDeTaiSV) 
                    VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :fileSanPham, :maDeTaiSV)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':fileSanPham', $this->FileSanPham);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);  // Đảm bảo MaDeTaiSV là khóa ngoại hợp lệ

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi thêm sản phẩm: " . $e->getMessage()];
        }
    }

    // Cập nhật sản phẩm
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET TenSanPham = :tenSanPham, NgayHoanThanh = :ngayHoanThanh, KetQua = :ketQua, FileSanPham = :fileSanPham 
                    WHERE MaSanPhamNCKHSV = :maSanPhamNCKHSV";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':tenSanPham', $this->TenSanPham);
            $stmt->bindParam(':ngayHoanThanh', $this->NgayHoanThanh);
            $stmt->bindParam(':ketQua', $this->KetQua);
            $stmt->bindParam(':fileSanPham', $this->FileSanPham);
            $stmt->bindParam(':maSanPhamNCKHSV', $this->MaSanPhamNCKHSV);

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi cập nhật sản phẩm: " . $e->getMessage()];
        }
    }

    // Kiểm tra sự tồn tại của MaDeTaiSV trong bảng DeTaiSV (bảng khóa ngoại)
    private function isDeTaiSVExist() {
        $query = "SELECT MaDeTaiSV FROM DeTaiSV WHERE MaDeTaiSV = :maDeTaiSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maDeTaiSV", $this->MaDeTaiSV);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Xóa sản phẩm
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaSanPhamNCKHSV = :maSanPhamNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSanPhamNCKHSV', $this->MaSanPhamNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi xóa sản phẩm: " . $e->getMessage()];
        }
    }
}
?>
