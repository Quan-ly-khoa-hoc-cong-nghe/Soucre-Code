<?php
class KeHoachNCKHSV
{
    private $conn;
    private $table_name = "KeHoachNCKHSV";

    public $MaKeHoachNCKHSV;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $KinhPhi;
    public $FileKeHoach;
    public $MaDeTaiSV;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả kế hoạch
    public function readAll()
    {
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
    public function add()
    {
        try {
            // Kiểm tra xem MaDeTaiSV có tồn tại trong bảng DeTaiSV không
            if (!$this->isDeTaiSVExist()) {
                return ["error" => "Lỗi: Mã đề tài sinh viên không tồn tại - MaDeTaiSV: " . $this->MaDeTaiSV];
            }

            // Kiểm tra thông tin đầu vào
            if (empty($this->NgayBatDau) || empty($this->NgayKetThuc) || empty($this->KinhPhi) || empty($this->FileKeHoach) || empty($this->MaDeTaiSV)) {
                return ["error" => "Lỗi: Thông tin đầu vào không đầy đủ - NgayBatDau: " . $this->NgayBatDau . ", NgayKetThuc: " . $this->NgayKetThuc . ", KinhPhi: " . $this->KinhPhi . ", FileKeHoach: " . $this->FileKeHoach . ", MaDeTaiSV: " . $this->MaDeTaiSV];
            }

            // Câu lệnh SQL INSERT cho bảng KeHoachNCKHSV
            $sql_kehoach = "INSERT INTO " . $this->table_name . " (NgayBatDau, NgayKetThuc, KinhPhi, FileKeHoach, MaDeTaiSV) 
                        VALUES (:ngayBatDau, :ngayKetThuc, :kinhPhi, :fileKeHoach, :maDeTaiSV)";

            // Chuẩn bị câu lệnh SQL
            $stmt_kehoach = $this->conn->prepare($sql_kehoach);

            // Gán các tham số cho câu lệnh SQL
            $stmt_kehoach->bindParam(':ngayBatDau', $this->NgayBatDau);
            $stmt_kehoach->bindParam(':ngayKetThuc', $this->NgayKetThuc);
            $stmt_kehoach->bindParam(':kinhPhi', $this->KinhPhi);
            $stmt_kehoach->bindParam(':fileKeHoach', $this->FileKeHoach);
            $stmt_kehoach->bindParam(':maDeTaiSV', $this->MaDeTaiSV);

            // Thực thi câu lệnh SQL thêm kế hoạch
            if (!$stmt_kehoach->execute()) {
                error_log("Lỗi khi thêm kế hoạch: " . implode(", ", $stmt_kehoach->errorInfo()));
                return ["error" => "Không thể thêm kế hoạch"];
            }

            return true;
        } catch (PDOException $e) {
            return ["error" => "Lỗi PDO: " . $e->getMessage()];
        }
    }

    // Cập nhật kế hoạch
    public function update()
    {
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
    private function isDeTaiSVExist()
    {
        $query = "SELECT MaDeTaiSV FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTaiSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maDeTaiSV", $this->MaDeTaiSV);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Kiểm tra xem có kết quả hay không
    }

    // Xóa kế hoạch
    public function delete()
    {
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
