<?php

class HoSoNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả hồ sơ
    public function getAllHoSo()
    {
        $stmt = $this->db->prepare("SELECT * FROM HoSoNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy hồ sơ theo mã hồ sơ
    public function getHoSoByMa($maHoSo)
    {
        $stmt = $this->db->prepare("SELECT * FROM HoSoNCKHSV WHERE MaHoSo = :maHoSo");
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm hồ sơ mới
    public function addHoSo($maHoSo, $ngayNop, $fileHoSo, $trangThai, $maKhoa)
    {
        // Kiểm tra nếu mã hồ sơ đã tồn tại
        if ($this->checkMaHoSoExists($maHoSo)) {
            return "Mã hồ sơ đã tồn tại.";
        }

        $stmt = $this->db->prepare("INSERT INTO HoSoNCKHSV (MaHoSo, NgayNop, FileHoSo, TrangThai, MaKhoa) 
                                    VALUES (:maHoSo, :ngayNop, :fileHoSo, :trangThai, :maKhoa)");
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->bindParam(':ngayNop', $ngayNop);
        $stmt->bindParam(':fileHoSo', $fileHoSo);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':maKhoa', $maKhoa);
        return $stmt->execute();
    }

    // Kiểm tra mã hồ sơ có tồn tại không
    public function checkMaHoSoExists($maHoSo)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM HoSoNCKHSV WHERE MaHoSo = :maHoSo");
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật hồ sơ
    public function updateHoSo($maHoSo, $ngayNop, $fileHoSo, $trangThai, $maKhoa)
    {
        // Kiểm tra nếu mã hồ sơ không tồn tại
        if (!$this->checkMaHoSoExists($maHoSo)) {
            return "Mã hồ sơ không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE HoSoNCKHSV 
                                    SET NgayNop = :ngayNop, FileHoSo = :fileHoSo, TrangThai = :trangThai, MaKhoa = :maKhoa 
                                    WHERE MaHoSo = :maHoSo");
        $stmt->bindParam(':maHoSo', $maHoSo);
        $stmt->bindParam(':ngayNop', $ngayNop);
        $stmt->bindParam(':fileHoSo', $fileHoSo);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':maKhoa', $maKhoa);
        return $stmt->execute();
    }

    // Xóa hồ sơ
    public function deleteHoSo($maHoSo)
    {
        // Kiểm tra nếu mã hồ sơ không tồn tại
        if (!$this->checkMaHoSoExists($maHoSo)) {
            return "Mã hồ sơ không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM HoSoNCKHSV WHERE MaHoSo = :maHoSo");
        $stmt->bindParam(':maHoSo', $maHoSo);
        return $stmt->execute();
    }
}
