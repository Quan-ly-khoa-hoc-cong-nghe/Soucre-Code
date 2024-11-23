<?php

class DeTaiNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả đề tài
    public function getAllDeTai()
    {
        $stmt = $this->db->prepare("SELECT * FROM DeTaiNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đề tài theo mã đề tài
    public function getDeTaiByMa($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đề tài mới
    public function addDeTai($maDeTai, $tenDeTai, $moTa, $trangThai, $fileHopDong, $maHoSo, $maNhom)
    {
        // Kiểm tra nếu mã đề tài đã tồn tại
        if ($this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài đã tồn tại.";
        }

        $stmt = $this->db->prepare("INSERT INTO DeTaiNCKHSV (MaDeTaiSV, TenDeTai, MoTa, TrangThai, FileHopDong, MaHoSo, MaNhomNCKHSV) 
                                    VALUES (:maDeTai, :tenDeTai, :moTa, :trangThai, :fileHopDong, :maHoSo, :maNhom)");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->bindParam(':tenDeTai', $tenDeTai);
        $stmt->bindParam(':moTa', $moTa);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':fileHopDong', $fileHopDong);
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->bindParam(':maNhom', $maNhom);
        return $stmt->execute();
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

    // Cập nhật đề tài
    public function updateDeTai($maDeTai, $tenDeTai, $moTa, $trangThai, $fileHopDong, $maHoSo, $maNhom)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE DeTaiNCKHSV 
                                    SET TenDeTai = :tenDeTai, MoTa = :moTa, TrangThai = :trangThai, FileHopDong = :fileHopDong, MaHoSo = :maHoSo, MaNhomNCKHSV = :maNhom 
                                    WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->bindParam(':tenDeTai', $tenDeTai);
        $stmt->bindParam(':moTa', $moTa);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':fileHopDong', $fileHopDong);
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->bindParam(':maNhom', $maNhom);
        return $stmt->execute();
    }

    // Xóa đề tài
    public function deleteDeTai($maDeTai)
    {
        // Kiểm tra nếu mã đề tài không tồn tại
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
