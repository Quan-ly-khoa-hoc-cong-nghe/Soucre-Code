<?php
class KeHoachNCKHSV {
    private $conn;
    private $table_name = "KeHoachNCKHSV";

    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $FileKeHoach;
    public $MaDeTaiSV;

    public function __construct($db) {
        $this->conn = $db;
    }

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

    public function add() {
        try {
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
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET NgayBatDau = :ngayBatDau, NgayKetThuc = :ngayKetThuc, KinhPhi = :kinhPhi, 
                        FileKeHoach = :fileKeHoach 
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':ngayBatDau', $this->NgayBatDau);
            $stmt->bindParam(':ngayKetThuc', $this->NgayKetThuc);
            $stmt->bindParam(':kinhPhi', $this->KinhPhi);
            $stmt->bindParam(':fileKeHoach', $this->FileKeHoach);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return ["error" => "Không thể cập nhật kế hoạch, hãy kiểm tra dữ liệu."];
            }
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
    

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
