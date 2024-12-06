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
    private function generateMaDeTaiNCKHGV() {
        // Đếm số dòng hiện tại trong bảng KeHoachNCKHGV
        $query = "SELECT COUNT(*) FROM KeHoachNCKHGV";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã đề tài mới theo định dạng DTNCGV + (count + 1)
        return "DTNCGV" . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // Ví dụ: DTNCGV001, DTNCGV002
    }
    

    // Lấy kế hoạch theo mã đề tài
    public function getPlanByMaDeTai($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM KeHoachNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    private function generateMaKeHoachNCKHGV() {
        // Đếm số dòng hiện tại trong bảng KeHoachNCKHGV
        $query = "SELECT COUNT(*) FROM KeHoachNCKHGV";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Tạo mã kế hoạch mới theo định dạng KH + (count + 1)
        return "KH" . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // Ví dụ: KH001, KH002
    }
    

    // Thêm kế hoạch mới
    public function addPlan($ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach)
    {
        // Tạo mã kế hoạch tự động
        $maKeHoach = $this->generateMaKeHoachNCKHGV();
        
        // Thêm kế hoạch mới vào cơ sở dữ liệu
        $stmt = $this->db->prepare("INSERT INTO KeHoachNCKHGV (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaKeHoachNCKHGV) 
                                    VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maKeHoach)");
        $stmt->bindParam(':ngayBatDau', $ngayBatDau);
        $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);
        $stmt->bindParam(':kinhPhi', $kinhPhi);
        $stmt->bindParam(':fileKeHoach', $fileKeHoach);
        $stmt->bindParam(':maKeHoach', $maKeHoach);
        
        // Thực thi và kiểm tra nếu thành công
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
        // Kiểm tra mã đề tài trước khi cập nhật
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;  // Không có mã đề tài để cập nhật
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
        // Kiểm tra mã đề tài trước khi xóa
        if (!$this->checkMaDeTaiExists($maDeTai)) {
            return false;  // Không có mã đề tài để xóa
        }

        $stmt = $this->db->prepare("DELETE FROM KeHoachNCKHGV WHERE MaDeTaiNCKHGV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        return $stmt->execute();
    }
}
?>
