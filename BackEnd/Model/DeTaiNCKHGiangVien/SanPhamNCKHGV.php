<?php

class SanPhamNCKHGV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả sản phẩm
    public function getAllProducts()
    {
        $stmt = $this->db->prepare("SELECT * FROM SanPhamNCKHGV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo mã đề tài
    public function getProductByMaDeTai($maDeTai)
    {
        if (empty($maDeTai)) {
            return false; // Nếu mã đề tài rỗng, không thực hiện truy vấn
        }

        $stmt = $this->db->prepare("SELECT * FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
   // Thêm sản phẩm mới
public function addProduct($tenSanPham, $ngayHoanThanh, $ketQua, $maDeTai, $fileSanPham)
{
    // Kiểm tra dữ liệu đầu vào
    if (empty($tenSanPham) || empty($ngayHoanThanh) || empty($ketQua) || empty($maDeTai) || empty($fileSanPham)) {
        return false; // Nếu dữ liệu không hợp lệ, không thực hiện thêm
    }

    $stmt = $this->db->prepare("INSERT INTO SanPhamNCKHGV (TenSanPham, NgayHoanThanh, KetQua, MaDeTaiNCKHGV, FileSanPham) 
                                VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :maDeTai, :fileSanPham)");
    $stmt->bindParam(':tenSanPham', $tenSanPham);
    $stmt->bindParam(':ngayHoanThanh', $ngayHoanThanh);
    $stmt->bindParam(':ketQua', $ketQua);
    $stmt->bindParam(':maDeTai', $maDeTai);
    $stmt->bindParam(':fileSanPham', $fileSanPham);

    if ($stmt->execute()) {
        return true;
    }

    return false;
}
public function updateKetQua($maSanPham, $ketQua)
{
    // Kiểm tra mã sản phẩm có tồn tại không
    if (!$this->checkMaSanPhamExists($maSanPham)) {
        return false;
    }

    // Cập nhật KetQua
    $stmt = $this->db->prepare("UPDATE SanPhamNCKHGV SET KetQua = :ketQua WHERE MaSanPhamNCKHGV = :maSanPham");
    $stmt->bindParam(':ketQua', $ketQua);
    $stmt->bindParam(':maSanPham', $maSanPham);

    return $stmt->execute();
}


    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        if (empty($maDeTai)) {
            return false; // Nếu mã đề tài rỗng, trả về false
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Kiểm tra mã sản phẩm có tồn tại không
    public function checkMaSanPhamExists($maSanPham)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM SanPhamNCKHGV WHERE MaSanPhamNCKHGV = :maSanPham");
        $stmt->bindParam(':maSanPham', $maSanPham);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật sản phẩm theo mã đề tài
    public function updateProductByMaDeTai($maDeTai, $tenSanPham, $ngayHoanThanh, $ketQua)
    {
        // Kiểm tra tồn tại mã đề tài
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        // Kiểm tra dữ liệu đầu vào
        if (empty($tenSanPham) || empty($ngayHoanThanh) || empty($ketQua)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE SanPhamNCKHGV 
            SET TenSanPham = :tenSanPham, NgayHoanThanh = :ngayHoanThanh, KetQua = :ketQua
            WHERE MaDeTaiNCKHGV = :maDeTai
        ");
        $stmt->bindParam(':tenSanPham', $tenSanPham);
        $stmt->bindParam(':ngayHoanThanh', $ngayHoanThanh);
        $stmt->bindParam(':ketQua', $ketQua);
        $stmt->bindParam(':maDeTai', $maDeTai);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Xóa sản phẩm theo mã đề tài
    public function deleteProductByMaDeTai($maDeTai)
    {
        // Kiểm tra tồn tại mã đề tài
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
