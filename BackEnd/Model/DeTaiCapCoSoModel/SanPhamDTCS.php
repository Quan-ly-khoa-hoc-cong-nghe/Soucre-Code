<?php
class SanPhamDTCS {
    private $conn;
    private $table = "SanPhamDTCS";

    public $MaSanPhamDTCS;  // Cập nhật tên khóa chính
    public $TenSanPham;      // Cập nhật tên thuộc tính
    public $NgayHoanThanh;   // Cập nhật tên thuộc tính
    public $KetQua;          // Cập nhật tên thuộc tính
    public $FileSanPham;     // Thêm trường FileSanPham
    public $MaDTCS;          // Khóa ngoại

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
        $query = "SELECT * FROM " . $this->table . " WHERE TenSanPham = ? AND MaDTCS = ?";  // Cập nhật tên trường
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenSanPham);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->MaDTCS);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm
    public function add() {
        $query = "INSERT INTO " . $this->table . " (TenSanPham, NgayHoanThanh, KetQua, FileSanPham, MaDTCS) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenSanPham);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->NgayHoanThanh);  // Cập nhật tên thuộc tính
        $stmt->bindParam(3, $this->KetQua);  // Cập nhật tên thuộc tính
        $stmt->bindParam(4, $this->FileSanPham);  // Thêm tham số FileSanPham
        $stmt->bindParam(5, $this->MaDTCS);  // Khóa ngoại

        return $stmt->execute();
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table . " SET NgayHoanThanh = ?, KetQua = ?, FileSanPham = ? WHERE TenSanPham = ? AND MaDTCS = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayHoanThanh);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->KetQua);  // Cập nhật tên thuộc tính
        $stmt->bindParam(3, $this->FileSanPham);  // Thêm tham số FileSanPham
        $stmt->bindParam(4, $this->TenSanPham);  // Cập nhật tên thuộc tính
        $stmt->bindParam(5, $this->MaDTCS);  // Khóa ngoại

        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE TenSanPham = ? AND MaDTCS = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->TenSanPham);  // Cập nhật tên thuộc tính
        $stmt->bindParam(2, $this->MaDTCS);  // Khóa ngoại

        return $stmt->execute();
    }
}
?>
