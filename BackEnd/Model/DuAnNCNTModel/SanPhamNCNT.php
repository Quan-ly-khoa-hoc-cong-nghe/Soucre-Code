<?php
class SanPhamNCNT {
    private $conn;
    private $table = "SanPhamNCNT";

    public $MaSanPhamNCNT;  // Khóa chính
    public $TenSanPham;     // Tên sản phẩm
    public $NgayHoanThanh;  // Ngày hoàn thành
    public $KetQua;         // Kết quả
    public $FileSanPham;    // Đường dẫn tệp sản phẩm
    public $MaDuAn;         // Mã dự án

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một sản phẩm
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaSanPhamNCNT = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaSanPhamNCNT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
    public function add() {
        $query = "INSERT INTO " . $this->table . " (TenSanPham, NgayHoanThanh, KetQua, FileSanPham, MaDuAn) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenSanPham);
        $stmt->bindParam(2, $this->NgayHoanThanh);
        $stmt->bindParam(3, $this->KetQua);
        $stmt->bindParam(4, $this->FileSanPham);
        $stmt->bindParam(5, $this->MaDuAn);
        return $stmt->execute();
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET TenSanPham = ?, NgayHoanThanh = ?, KetQua = ?, FileSanPham = ?, MaDuAn = ? 
                  WHERE MaSanPhamNCNT = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenSanPham);
        $stmt->bindParam(2, $this->NgayHoanThanh);
        $stmt->bindParam(3, $this->KetQua);
        $stmt->bindParam(4, $this->FileSanPham);
        $stmt->bindParam(5, $this->MaDuAn);
        $stmt->bindParam(6, $this->MaSanPhamNCNT);
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaSanPhamNCNT = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaSanPhamNCNT);
        return $stmt->execute();
    }
}
?>
