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
        $stmt = $this->db->prepare("SELECT * FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
    public function addProduct($tenSanPham, $ngayHoanThanh, $ketQua, $maDeTai)
    {
        $stmt = $this->db->prepare("INSERT INTO SanPhamNCKHGV (TenSanPham, NgayHoanThanh, KetQua, MaDeTaiNCKHGV) 
                                    VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :maDeTai)");
        $stmt->bindParam(':tenSanPham', $tenSanPham);
        $stmt->bindParam(':ngayHoanThanh', $ngayHoanThanh);
        $stmt->bindParam(':ketQua', $ketQua);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật sản phẩm theo mã đề tài
    public function updateProductByMaDeTai($maDeTai, $tenSanPham, $ngayHoanThanh, $ketQua)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
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
        return $stmt->execute();
    }

    // Xóa sản phẩm theo mã đề tài
    public function deleteProductByMaDeTai($maDeTai)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM SanPhamNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
