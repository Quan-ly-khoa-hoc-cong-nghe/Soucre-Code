<?php
class KeHoachNCKHSV {
    private $conn;
    private $table_name = "KeHoachNCKHSV";

    public $MaKeHoachNCKHSV;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $FileKeHoach;
    public $MaDeTaiSV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY NgayBatDau ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm kế hoạch mới
    public function add() {
        try {
            // Kiểm tra xem MaDeTaiSV có tồn tại trong bảng DeTaiSV không
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            $sql = "INSERT INTO " . $this->table_name . " (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaDeTaiSV) 
                    VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maDeTaiSV)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':ngayBatDau', $this->NgayBatDau);
            $stmt->bindParam(':ngayKetThuc', $this->NgayKetThuc);
            $stmt->bindParam(':kinhPhi', $this->KinhPhi);
            $stmt->bindParam(':fileKeHoach', $this->FileKeHoach);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi thêm kế hoạch: " . $e->getMessage()];
        }
    }

    // Cập nhật kế hoạch
    public function update() {
        try {
            // Kiểm tra xem MaDeTaiSV có tồn tại trong bảng DeTaiSV không
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            $sql = "UPDATE " . $this->table_name . " 
                    SET NgayBatDau = :ngayBatDau, NgayKetThuc = :ngayKetThuc, KinhPhi = :kinhPhi, FileKeHoach = :fileKeHoach 
                    WHERE MaKeHoachNCKHSV = :maKeHoachNCKHSV";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':ngayBatDau', $this->NgayBatDau);
            $stmt->bindParam(':ngayKetThuc', $this->NgayKetThuc);
            $stmt->bindParam(':kinhPhi', $this->KinhPhi);
            $stmt->bindParam(':fileKeHoach', $this->FileKeHoach);
            $stmt->bindParam(':maKeHoachNCKHSV', $this->MaKeHoachNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi cập nhật kế hoạch: " . $e->getMessage()];
        }
    }

    // Kiểm tra sự tồn tại của MaDeTaiSV trong bảng DeTaiSV (bảng khóa ngoại)
    private function isDeTaiSVExist() {
        $query = "SELECT MaDeTaiSV FROM DeTaiSV WHERE MaDeTaiSV = :maDeTaiSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maDeTaiSV", $this->MaDeTaiSV);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Xóa kế hoạch
    public function delete() {
        try {
            // Kiểm tra xem MaDeTaiSV có tồn tại trong bảng DeTaiSV không
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            $sql = "DELETE FROM " . $this->table_name . " WHERE MaKeHoachNCKHSV = :maKeHoachNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maKeHoachNCKHSV', $this->MaKeHoachNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi xóa kế hoạch: " . $e->getMessage()];
        }
    }
}
?>
