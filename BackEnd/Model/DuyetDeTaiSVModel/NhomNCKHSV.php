<?php
class NhomNCKHSV {
    private $conn;
    private $table_name = "NhomNCKHSV";

    public $MaNhomNCKHSV;
    public $MaDeTaiSV;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaNhomNCKHSV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }
    public function autoUpdateGroups($deTaiData) {
        try {
            foreach ($deTaiData as $deTai) {
                // Kiểm tra xem MaNhomNCKHSV đã tồn tại trong bảng chưa
                $sqlCheck = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->bindParam(':maNhomNCKHSV', $deTai['MaNhomNCKHSV']);
                $stmtCheck->execute();
                $exists = $stmtCheck->fetchColumn();
    
                if ($exists) {
                    // Nếu mã nhóm đã tồn tại, cập nhật MaDeTaiSV
                    $sqlUpdate = "UPDATE " . $this->table_name . " 
                                  SET MaDeTaiSV = :maDeTaiSV 
                                  WHERE MaNhomNCKHSV = :maNhomNCKHSV";
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':maDeTaiSV', $deTai['MaDeTaiSV']);
                    $stmtUpdate->bindParam(':maNhomNCKHSV', $deTai['MaNhomNCKHSV']);
                    $stmtUpdate->execute();
                } else {
                    // Nếu mã nhóm chưa tồn tại, thêm mới
                    $sqlInsert = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaDeTaiSV) 
                                  VALUES (:maNhomNCKHSV, :maDeTaiSV)";
                    $stmtInsert = $this->conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':maNhomNCKHSV', $deTai['MaNhomNCKHSV']);
                    $stmtInsert->bindParam(':maDeTaiSV', $deTai['MaDeTaiSV']);
                    $stmtInsert->execute();
                }
            }
            return ["success" => true, "message" => "Đồng bộ dữ liệu thành công"];
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
    
    
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaDeTaiSV) VALUES (:maDeTaiSV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
    public function addNewGroup() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaDeTaiSV) VALUES (:maNhomNCKHSV, :maDeTaiSV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
    

    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " SET MaDeTaiSV = :maDeTaiSV WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
