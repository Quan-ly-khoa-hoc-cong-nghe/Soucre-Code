<?php
class SinhVien {
    private $conn;
    private $table_name = "SinhVien";

    public $MaSinhVien;
    public $TenSinhVien;
    public $EmailSV;
    public $sdtSV;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenSinhVien ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    public function add() {
        try {
            // Kiểm tra nếu các giá trị cần thiết có tồn tại
            if (empty($this->MaSinhVien) || empty($this->TenSinhVien) || empty($this->EmailSV) || empty($this->sdtSV)) {
                return false;
            }
    
            // Thực hiện câu lệnh SQL để thêm sinh viên vào cơ sở dữ liệu
            $sql = "INSERT INTO " . $this->table_name . " (MaSinhVien, TenSinhVien, EmailSV, sdtSV) 
                    VALUES (:maSinhVien, :tenSinhVien, :emailSV, :sdtSV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':tenSinhVien', $this->TenSinhVien);
            $stmt->bindParam(':emailSV', $this->EmailSV);
            $stmt->bindParam(':sdtSV', $this->sdtSV);
    
            return $stmt->execute();  // Trả về true nếu thêm thành công, false nếu không thành công
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET TenSinhVien = :tenSinhVien, EmailSV = :emailSV, sdtSV = :sdtSV 
                    WHERE MaSinhVien = :maSinhVien";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':tenSinhVien', $this->TenSinhVien);
            $stmt->bindParam(':emailSV', $this->EmailSV);
            $stmt->bindParam(':sdtSV', $this->sdtSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaSinhVien = :maSinhVien";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
