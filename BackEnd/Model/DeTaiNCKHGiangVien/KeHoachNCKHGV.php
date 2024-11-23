<?php

class KeHoachNCKHGV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả kế hoạch
    public function getAllPlans()
    {
        $stmt = $this->db->prepare("SELECT * FROM KeHoachNCKHGV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy kế hoạch theo mã đề tài
    public function getPlanByMaDeTai($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM KeHoachNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm kế hoạch mới
    public function addPlan($ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach, $maDeTai)
    {
        $stmt = $this->db->prepare("INSERT INTO KeHoachNCKHGV (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaDeTaiNCKHGV) 
                                    VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maDeTai)");
        $stmt->bindParam(':ngayBatDau', $ngayBatDau);
        $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);
        $stmt->bindParam(':kinhPhi', $kinhPhi);
        $stmt->bindParam(':fileKeHoach', $fileKeHoach);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM KeHoachNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật kế hoạch theo mã đề tài
    public function updatePlanByMaDeTai($maDeTai, $ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE KeHoachNCKHGV 
            SET NgayBatDau = :ngayBatDau, NgayKetThuc = :ngayKetThuc, KinhPhi = :kinhPhi, FileKeHoach = :fileKeHoach
            WHERE MaDeTaiNCKHGV = :maDeTai
        ");
        $stmt->bindParam(':ngayBatDau', $ngayBatDau);
        $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);
        $stmt->bindParam(':kinhPhi', $kinhPhi);
        $stmt->bindParam(':fileKeHoach', $fileKeHoach);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Xóa kế hoạch theo mã đề tài
    public function deletePlanByMaDeTai($maDeTai)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM KeHoachNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
