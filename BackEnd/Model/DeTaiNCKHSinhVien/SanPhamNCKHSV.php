<?php

class SanPhamNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả sản phẩm
    public function getAllSanPham()
    {
        $stmt = $this->db->prepare("SELECT * FROM SanPhamNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo mã đề tài
    public function getSanPhamByMaDeTai($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM SanPhamNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Thêm sản phẩm mới
    public function addSanPham($tenSanPham, $ngayHoanThanh, $ketQua, $maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("INSERT INTO SanPhamNCKHSV (TenSanPham, NgayHoanThanh, KetQua, MaDeTaiSV) 
                                    VALUES (:tenSanPham, :ngayHoanThanh, :ketQua, :maDeTai)");
        $stmt->bindParam(':tenSanPham', $tenSanPham);
        $stmt->bindParam(':ngayHoanThanh', $ngayHoanThanh);
        $stmt->bindParam(':ketQua', $ketQua);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Cập nhật sản phẩm
    public function updateSanPham($tenSanPham, $ngayHoanThanh, $ketQua, $maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE SanPhamNCKHSV 
                                    SET TenSanPham = :tenSanPham, NgayHoanThanh = :ngayHoanThanh, KetQua = :ketQua 
                                    WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':tenSanPham', $tenSanPham);
        $stmt->bindParam(':ngayHoanThanh', $ngayHoanThanh);
        $stmt->bindParam(':ketQua', $ketQua);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Xóa sản phẩm theo mã đề tài
    public function deleteSanPhamByMaDeTai($maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM SanPhamNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
