<?php

class NhomNCKHSV
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Lấy tất cả các nhóm
    public function getAllGroups()
    {
        $stmt = $this->db->prepare("SELECT * FROM NhomNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy nhóm theo mã nhóm
    public function getGroupByMaNhom($maNhom)
    {
        $stmt = $this->db->prepare("SELECT * FROM NhomNCKHSV WHERE MaNhomNCKHSV = :maNhom");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm nhóm mới
    public function addGroup($maNhom, $maDeTaiSV)
    {
        // Kiểm tra nếu mã nhóm đã tồn tại
        if ($this->checkMaNhomExists($maNhom)) {
            return "Mã nhóm đã tồn tại.";
        }

        $stmt = $this->db->prepare("INSERT INTO NhomNCKHSV (MaNhomNCKHSV, MaDeTaiSV) VALUES (:maNhom, :maDeTaiSV)");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maDeTaiSV', $maDeTaiSV);
        return $stmt->execute();
    }

    // Kiểm tra mã nhóm có tồn tại không
    public function checkMaNhomExists($maNhom)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM NhomNCKHSV WHERE MaNhomNCKHSV = :maNhom and MaDeTaiSV = :maDeTaiSV");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật nhóm theo mã nhóm
    public function updateGroup($maNhom, $maDeTaiSV)
    {
        // Kiểm tra nếu mã nhóm không tồn tại
        if (!$this->checkMaNhomExists($maNhom)) {
            return "Mã nhóm không tồn tại.";
        }

        $stmt = $this->db->prepare("UPDATE NhomNCKHSV SET MaDeTaiSV = :maDeTaiSV WHERE MaNhomNCKHSV = :maNhom");
        $stmt->bindParam(':maNhom', $maNhom);
        $stmt->bindParam(':maDeTaiSV', $maDeTaiSV);
        return $stmt->execute();
    }

    // Xóa nhóm theo mã nhóm
    public function deleteGroup($maNhom)
    {
        // Kiểm tra nếu mã nhóm không tồn tại
        if (!$this->checkMaNhomExists($maNhom)) {
            return "Mã nhóm không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM NhomNCKHSV WHERE MaNhomNCKHSV = :maNhom");
        $stmt->bindParam(':maNhom', $maNhom);
        return $stmt->execute();
    }
}
