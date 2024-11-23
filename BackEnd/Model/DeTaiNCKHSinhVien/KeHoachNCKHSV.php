<?php

class KeHoachNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả kế hoạch
    public function getAllKeHoach()
    {
        $stmt = $this->db->prepare("SELECT * FROM KeHoachNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy kế hoạch theo mã đề tài
    public function getKeHoachByMaDeTai($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM KeHoachNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra mã đề tài có tồn tại trong bảng `DeTaiNCKHSV`
    public function checkMaDeTaiExists($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Thêm kế hoạch mới
    public function addKeHoach($ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach, $maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("INSERT INTO KeHoachNCKHSV (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaDeTaiSV) 
                                    VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maDeTai)");
        $stmt->bindParam(':ngayBatDau', $ngayBatDau);
        $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);
        $stmt->bindParam(':kinhPhi', $kinhPhi);
        $stmt->bindParam(':fileKeHoach', $fileKeHoach);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Cập nhật kế hoạch
    public function updateKeHoach($ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach, $maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE KeHoachNCKHSV 
                                    SET NgayBatDau = :ngayBatDau, NgayKetThuc = :ngayKetThuc, KinhPhi = :kinhPhi, FileKeHoach = :fileKeHoach 
                                    WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':ngayBatDau', $ngayBatDau);
        $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);
        $stmt->bindParam(':kinhPhi', $kinhPhi);
        $stmt->bindParam(':fileKeHoach', $fileKeHoach);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Xóa kế hoạch theo mã đề tài
    public function deleteKeHoach($maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM KeHoachNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
