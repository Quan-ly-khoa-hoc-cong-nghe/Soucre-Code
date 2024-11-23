<?php

class BaoCaoDinhKy
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả báo cáo
    public function getAllReports()
    {
        $stmt = $this->db->prepare("SELECT * FROM BaoCaoDinhKy");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy báo cáo theo mã đề tài
    public function getReportByMaDeTai($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm báo cáo mới
    public function addReport($noiDung, $ngayNop, $fileBaoCao, $maDeTai)
    {
        $stmt = $this->db->prepare("INSERT INTO BaoCaoDinhKy (NoiDungBaoCao, NgayNop, FileBaoBao, MaDeTaiNCKHGV) 
                                    VALUES (:noiDung, :ngayNop, :fileBaoCao, :maDeTai)");
        $stmt->bindParam(':noiDung', $noiDung);
        $stmt->bindParam(':ngayNop', $ngayNop);
        $stmt->bindParam(':fileBaoCao', $fileBaoCao);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật báo cáo theo mã đề tài
    public function updateReportByMaDeTai($maDeTai, $noiDung, $ngayNop, $fileBaoCao)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE BaoCaoDinhKy 
            SET NoiDungBaoCao = :noiDung, NgayNop = :ngayNop, FileBaoBao = :fileBaoCao
            WHERE MaDeTaiNCKHGV = :maDeTai
        ");
        $stmt->bindParam(':noiDung', $noiDung);
        $stmt->bindParam(':ngayNop', $ngayNop);
        $stmt->bindParam(':fileBaoCao', $fileBaoCao);
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }

    // Xóa báo cáo theo mã đề tài
    public function deleteReportByMaDeTai($maDeTai)
    {
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
