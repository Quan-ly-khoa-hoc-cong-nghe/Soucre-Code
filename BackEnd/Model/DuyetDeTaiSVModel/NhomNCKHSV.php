<?php
class NhomNCKHSV
{
    private $conn;
    private $table_name = "NhomNCKHSV";

    public $MaNhomNCKHSV;  // Trường này là AUTO_INCREMENT, không cần phải truyền vào khi thêm mới
    public $MaDeTaiSV;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả nhóm nghiên cứu
    public function readAll()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaNhomNCKHSV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Kiểm tra sự tồn tại của MaDeTaiSV trong bảng DeTaiSV
    private function isDeTaiSVExist()
    {
        $query = "SELECT MaDeTaiSV FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTaiSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maDeTaiSV", $this->MaDeTaiSV);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Thêm nhóm nghiên cứu mới
    public function add()
    {
        try {
            // Kiểm tra sự tồn tại của MaDeTaiSV trong bảng DeTaiSV
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            // Câu lệnh SQL không cần truyền MaNhomNCKHSV vì nó sẽ tự tăng
            $query = "INSERT INTO " . $this->table_name . " (MaDeTaiSV) VALUES (:MaDeTaiSV)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':MaDeTaiSV', $this->MaDeTaiSV, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Lấy MaNhomNCKHSV tự động sinh ra từ lastInsertId()
                $this->MaNhomNCKHSV = $this->conn->lastInsertId();
                return $this->MaNhomNCKHSV;  // Trả về giá trị MaNhomNCKHSV
            } else {
                $errorInfo = $stmt->errorInfo();
                return ["error" => "Lỗi khi thêm nhóm nghiên cứu: " . implode(" | ", $errorInfo)];
            }
        } catch (PDOException $e) {
            return ["error" => "PDOException khi thêm nhóm nghiên cứu: " . $e->getMessage()];
        }
    }

    // Cập nhật nhóm nghiên cứu
    public function update()
    {
        try {
            // Kiểm tra sự tồn tại của MaDeTaiSV trong bảng DeTaiSV
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Mã đề tài sinh viên không tồn tại"];
            }

            $sql = "UPDATE " . $this->table_name . " SET MaDeTaiSV = :maDeTaiSV WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Xóa nhóm nghiên cứu
    public function delete()
    {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật hoặc thêm nhóm nghiên cứu tự động
    public function autoUpdateGroups($deTaiData)
    {
        try {
            foreach ($deTaiData as $deTai) {
                // Kiểm tra sự tồn tại của MaDeTaiSV trước khi thực hiện thao tác
                $this->MaDeTaiSV = $deTai['MaDeTaiSV'];
                if (!$this->isDeTaiSVExist()) {
                    return ["error" => "Mã đề tài sinh viên không tồn tại: " . $deTai['MaDeTaiSV']];
                }

                // Kiểm tra xem MaNhomNCKHSV đã tồn tại trong bảng chưa
                $sqlCheck = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->bindParam(':maNhomNCKHSV', $deTai['MaNhomNCKHSV']);
                $stmtCheck->execute();
                $exists = $stmtCheck->fetchColumn();

                if ($exists) {
                    // Nếu mã nhóm đã tồn tại, cập nhật MaDeTaiSV
                    $sqlUpdate = "UPDATE " . $this->table_name . " SET MaDeTaiSV = :maDeTaiSV WHERE MaNhomNCKHSV = :maNhomNCKHSV";
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':maDeTaiSV', $deTai['MaDeTaiSV']);
                    $stmtUpdate->bindParam(':maNhomNCKHSV', $deTai['MaNhomNCKHSV']);
                    $stmtUpdate->execute();
                } else {
                    // Nếu mã nhóm chưa tồn tại, thêm mới
                    $sqlInsert = "INSERT INTO " . $this->table_name . " (MaDeTaiSV) VALUES (:maDeTaiSV)";
                    $stmtInsert = $this->conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':maDeTaiSV', $deTai['MaDeTaiSV']);
                    $stmtInsert->execute();
                }
            }
            return ["success" => true, "message" => "Đồng bộ dữ liệu thành công"];
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
