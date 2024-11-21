<?php
class GiangVien {
    private $conn;
    private $table_name = "GiangVien";

    public $MaGV;
    public $HoTenGV;
    public $NgaySinhGV;
    public $EmailGV;
    public $DiaChiGV;
    public $DiemNCKH;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY HoTenGV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (HoTenGV, NgaySinhGV, EmailGV, DiaChiGV, DiemNCKH, MaKhoa) 
                    VALUES (:hoTenGV, :ngaySinhGV, :emailGV, :diaChiGV, :diemNCKH, :maKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':hoTenGV', $this->HoTenGV);
            $stmt->bindParam(':ngaySinhGV', $this->NgaySinhGV);
            $stmt->bindParam(':emailGV', $this->EmailGV);
            $stmt->bindParam(':diaChiGV', $this->DiaChiGV);
            $stmt->bindParam(':diemNCKH', $this->DiemNCKH);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET HoTenGV = :hoTenGV, NgaySinhGV = :ngaySinhGV, EmailGV = :emailGV, 
                        DiaChiGV = :diaChiGV, DiemNCKH = :diemNCKH, MaKhoa = :maKhoa 
                    WHERE MaGV = :maGV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maGV', $this->MaGV);
            $stmt->bindParam(':hoTenGV', $this->HoTenGV);
            $stmt->bindParam(':ngaySinhGV', $this->NgaySinhGV);
            $stmt->bindParam(':emailGV', $this->EmailGV);
            $stmt->bindParam(':diaChiGV', $this->DiaChiGV);
            $stmt->bindParam(':diemNCKH', $this->DiemNCKH);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaGV = :maGV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maGV', $this->MaGV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
