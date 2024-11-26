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
        if (empty($maDeTai)) {
            return false; // Nếu mã đề tài rỗng, không thực hiện truy vấn
        }

        $stmt = $this->db->prepare("SELECT * FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm báo cáo mới
    public function addReport($noiDung, $ngayNop, $fileBaoCao, $maDeTai)
    {
        // Kiểm tra dữ liệu đầu vào
        if (empty($noiDung) || empty($ngayNop) || empty($fileBaoCao) || empty($maDeTai)) {
            return false; // Nếu dữ liệu không hợp lệ, không thực hiện thêm
        }

        $stmt = $this->db->prepare("INSERT INTO BaoCaoDinhKy (NoiDungBaoCao, NgayNop, FileBaoBao, MaDeTaiNCKHGV) 
                                    VALUES (:noiDung, :ngayNop, :fileBaoCao, :maDeTai)");
        $stmt->bindParam(':noiDung', $noiDung);
        $stmt->bindParam(':ngayNop', $ngayNop);
        $stmt->bindParam(':fileBaoCao', $fileBaoCao);
        $stmt->bindParam(':maDeTai', $maDeTai);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists($maDeTai)
    {
        if (empty($maDeTai)) {
            return false; // Nếu mã đề tài rỗng, trả về false
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Kiểm tra mã báo cáo có tồn tại không
    public function checkMaBaoCaoExists($maBaoCao)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM BaoCaoDinhKy WHERE MaBaoCaoDinhKy = :maBaoCao");
        $stmt->bindParam(':maBaoCao', $maBaoCao);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật báo cáo theo mã đề tài
    public function updateReportByMaDeTai($maDeTai, $noiDung, $ngayNop, $fileBaoCao)
    {
        // Kiểm tra tồn tại mã đề tài
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        // Kiểm tra dữ liệu đầu vào
        if (empty($noiDung) || empty($ngayNop) || empty($fileBaoCao)) {
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

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Xóa báo cáo theo mã đề tài
    public function deleteReportByMaDeTai($maDeTai)
    {
        // Kiểm tra tồn tại mã đề tài
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM BaoCaoDinhKy WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
