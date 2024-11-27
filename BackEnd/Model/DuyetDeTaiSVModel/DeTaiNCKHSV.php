<?php
class DeTaiNCKHSV
{
    private $conn;
    private $table_name = "DeTaiNCKHSV";

    public $maDeTaiSV;
    public $tenDeTai;
    public $moTa;
    public $trangThai;
    public $fileHopDong;
    public $maHoSo;
    public $maNhomNCKHSV;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức sinh mã tự động
    private function generateMaDeTaiSV()
    {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Tạo mã đề tài mới theo định dạng DTNCSV + (count + 1)
        return "DTNCSV" . ($count + 1);
    }

    // Thêm đề tài mới
    public function add()
    {
        try {
            // Tạo mã đề tài tự động
            $this->maDeTaiSV = $this->generateMaDeTaiSV();

            $query = "INSERT INTO " . $this->table_name . " 
                        (maDeTaiSV, tenDeTai, moTa, trangThai, fileHopDong, maHoSo, maNhomNCKHSV) 
                        VALUES (:maDeTaiSV, :tenDeTai, :moTa, :trangThai, :fileHopDong, :maHoSo, :maNhomNCKHSV)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDeTaiSV', $this->maDeTaiSV);
            $stmt->bindParam(':tenDeTai', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->maNhomNCKHSV);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật thông tin đề tài
    public function update()
    {
        try {
            $query = "UPDATE " . $this->table_name . " 
                    SET TenDeTai = :tenDeTai, MoTa = :moTa, TrangThai = :trangThai, 
                        FileHopDong = :fileHopDong, MaHoSo = :maHoSo, MaNhomNCKHSV = :maNhomNCKHSV 
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':maDeTaiSV', $this->maDeTaiSV);
            $stmt->bindParam(':tenDeTai', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->maNhomNCKHSV);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Lấy tất cả đề tài
    public function readAll()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY TenDeTai ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Lấy một đề tài theo mã
    public function readByMaDeTaiSV($maDeTaiSV)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE maDeTaiSV = :maDeTaiSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maDeTaiSV', $maDeTaiSV);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false; // Không tìm thấy dữ liệu
        }
    }

    // Xóa đề tài theo mã
    public function delete()
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDeTaiSV', $this->maDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
