<?php

class LoaiHinhNCKH
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả loại hình NCKH
    public function getAllLoaiHinh()
    {
        $stmt = $this->db->prepare("SELECT * FROM LoaiHinhNCKH");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy loại hình NCKH theo mã loại hình
    public function getLoaiHinhByMa($maLoaiHinh)
    {
        $stmt = $this->db->prepare("SELECT * FROM LoaiHinhNCKH WHERE MaLoaiHinhNCKH = :maLoaiHinh");
        $stmt->bindParam(':maLoaiHinh', $maLoaiHinh);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm loại hình NCKH mới
    public function addLoaiHinh($maLoaiHinh, $tenLoaiHinh, $diemSo)
    {
        $stmt = $this->db->prepare("INSERT INTO LoaiHinhNCKH (MaLoaiHinhNCKH, TenLoaiHinh, DiemSo) 
                                    VALUES (:maLoaiHinh, :tenLoaiHinh, :diemSo)");
        $stmt->bindParam(':maLoaiHinh', $maLoaiHinh);
        $stmt->bindParam(':tenLoaiHinh', $tenLoaiHinh);
        $stmt->bindParam(':diemSo', $diemSo);
        return $stmt->execute();
    }

    // Kiểm tra mã loại hình có tồn tại không
    public function checkMaLoaiHinhExists($maLoaiHinh)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM LoaiHinhNCKH WHERE MaLoaiHinhNCKH = :maLoaiHinh");
        $stmt->bindParam(':maLoaiHinh', $maLoaiHinh);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật loại hình NCKH
    public function updateLoaiHinh($maLoaiHinh, $tenLoaiHinh, $diemSo)
    {
        if (!$this->checkMaLoaiHinhExists($maLoaiHinh)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE LoaiHinhNCKH 
            SET TenLoaiHinh = :tenLoaiHinh, DiemSo = :diemSo
            WHERE MaLoaiHinhNCKH = :maLoaiHinh
        ");
        $stmt->bindParam(':maLoaiHinh', $maLoaiHinh);
        $stmt->bindParam(':tenLoaiHinh', $tenLoaiHinh);
        $stmt->bindParam(':diemSo', $diemSo);
        return $stmt->execute();
    }

    // Xóa loại hình NCKH
    public function deleteLoaiHinh($maLoaiHinh)
    {
        if (!$this->checkMaLoaiHinhExists($maLoaiHinh)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM LoaiHinhNCKH WHERE MaLoaiHinhNCKH = :maLoaiHinh");
        $stmt->bindParam(':maLoaiHinh', $maLoaiHinh);
        return $stmt->execute();
    }
}
