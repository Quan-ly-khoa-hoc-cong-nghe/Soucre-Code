<?php

class GiangVienNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả giảng viên NCKHSV
    public function getAllRecords()
    {
        $stmt = $this->db->prepare("SELECT * FROM GiangVienNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy giảng viên theo mã nhóm NCKHSV
    public function getByMaNhom($maNhom)
    {
        $stmt = $this->db->prepare("SELECT * FROM GiangVienNCKHSV WHERE MaNhomNCKHSV = :maNhom");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm giảng viên mới vào nhóm NCKHSV
    public function addRecord($maNhom, $maGV)
    {
        $stmt = $this->db->prepare("INSERT INTO GiangVienNCKHSV (MaNhomNCKHSV, MaGV) 
                                    VALUES (:maNhom, :maGV)");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maGV', $maGV);
        return $stmt->execute();
    }

    // Kiểm tra bản ghi có tồn tại không
    public function checkRecordExists($maNhom, $maGV)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM GiangVienNCKHSV 
                                    WHERE MaNhomNCKHSV = :maNhom AND MaGV = :maGV");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maGV', $maGV);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật giảng viên trong nhóm NCKHSV
    public function updateRecord($maNhom, $maGV, $newMaGV)
    {
        if (!$this->checkRecordExists($maNhom, $maGV)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE GiangVienNCKHSV 
            SET MaGV = :newMaGV
            WHERE MaNhomNCKHSV = :maNhom AND MaGV = :maGV
        ");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maGV', $maGV);
        $stmt->bindParam(':newMaGV', $newMaGV);
        return $stmt->execute();
    }

    // Xóa giảng viên khỏi nhóm NCKHSV
    public function deleteRecord($maNhom, $maGV)
    {
        if (!$this->checkRecordExists($maNhom, $maGV)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM GiangVienNCKHSV WHERE MaNhomNCKHSV = :maNhom AND MaGV = :maGV");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maGV', $maGV);
        return $stmt->execute();
    }
}
