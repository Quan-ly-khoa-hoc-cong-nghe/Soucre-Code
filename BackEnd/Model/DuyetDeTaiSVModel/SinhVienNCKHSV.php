<?php
class SinhVienNCKHSV {
    private $conn;
    private $table_name = "SinhVienNCKHSV";

    public $MaNhomNCKHSV;
    public $MaSinhVien;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaSinhVien ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    public function add() {
        try {
            // Thêm dữ liệu cho MaNhomNCKHSV và MaSinhVien
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaSinhVien) VALUES (:maNhomNCKHSV, :maSinhVien)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function update() {
        try {
            // Cập nhật thông tin MaSinhVien theo MaNhomNCKHSV
            $sql = "UPDATE " . $this->table_name . " SET MaSinhVien = :maSinhVien WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maSinhVien', $this->MaSinhVien);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function autoUpdateFromAPI($apiUrl) {
        try {
            // Lấy dữ liệu từ API
            $jsonData = file_get_contents($apiUrl);
            $data = json_decode($jsonData, true);
    
            if (isset($data['NhomNCKHSV'])) {
                foreach ($data['NhomNCKHSV'] as $item) {
                    if (!empty($item['MaNhomNCKHSV'])) {
                        // Kiểm tra xem `MaNhomNCKHSV` đã tồn tại trong bảng chưa
                        $checkSql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
                        $checkStmt = $this->conn->prepare($checkSql);
                        $checkStmt->bindParam(':maNhomNCKHSV', $item['MaNhomNCKHSV']);
                        $checkStmt->execute();
                        $count = $checkStmt->fetchColumn();
    
                        if ($count == 0) {
                            // Nếu chưa tồn tại, thêm mới
                            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaSinhVien) VALUES (:maNhomNCKHSV, NULL)";
                            $stmt = $this->conn->prepare($sql);
                            $stmt->bindParam(':maNhomNCKHSV', $item['MaNhomNCKHSV']);
                            $stmt->execute();
                        }
                    }
                }
                return ['message' => 'Cập nhật dữ liệu từ API thành công'];
            } else {
                return ['error' => 'Dữ liệu từ API không hợp lệ'];
            }
        } catch (Exception $e) {
            return ['error' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    public function delete() {
        try {
            // Xóa thông tin theo MaNhomNCKHSV
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
