<?php

class SinhVienNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả các sinh viên
    public function getAllRecords()
    {
        $stmt = $this->db->prepare("SELECT * FROM SinhVienNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sinh viên theo mã nhóm
    public function getByMaNhom($maNhom)
    {
        $stmt = $this->db->prepare("SELECT * FROM SinhVienNCKHSV WHERE MaNhomNCKHSV = :maNhom");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sinh viên vào nhóm
    public function addRecord($maNhom, $maSinhVien)
    {
        // Kiểm tra nếu bản ghi đã tồn tại
        if ($this->checkRecordExists($maNhom, $maSinhVien)) {
            return "Sinh viên đã tồn tại trong nhóm.";
        }

        $stmt = $this->db->prepare("INSERT INTO SinhVienNCKHSV (MaNhomNCKHSV, MaSinhVien) VALUES (:maNhom, :maSinhVien)");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maSinhVien', $maSinhVien);
        return $stmt->execute();
    }

    // Kiểm tra xem bản ghi có tồn tại không
    public function checkRecordExists($maNhom, $maSinhVien)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM SinhVienNCKHSV WHERE MaNhomNCKHSV = :maNhom AND MaSinhVien = :maSinhVien");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maSinhVien', $maSinhVien);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật sinh viên trong nhóm
    public function updateRecord($maNhom, $maSinhVien, $newMaSinhVien)
    {
        // Kiểm tra nếu bản ghi không tồn tại
        if (!$this->checkRecordExists($maNhom, $maSinhVien)) {
            return "Bản ghi không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE SinhVienNCKHSV SET MaSinhVien = :newMaSinhVien WHERE MaNhomNCKHSV = :maNhom AND MaSinhVien = :maSinhVien");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maSinhVien', $maSinhVien);
        $stmt->bindParam(':newMaSinhVien', $newMaSinhVien);
        return $stmt->execute();
    }

    // Xóa sinh viên khỏi nhóm
    public function deleteRecord($maNhom, $maSinhVien)
    {
        // Kiểm tra nếu bản ghi không tồn tại
        if (!$this->checkRecordExists($maNhom, $maSinhVien)) {
            return "Bản ghi không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM SinhVienNCKHSV WHERE MaNhomNCKHSV = :maNhom AND MaSinhVien = :maSinhVien");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maSinhVien', $maSinhVien);
        return $stmt->execute();
    }
}
