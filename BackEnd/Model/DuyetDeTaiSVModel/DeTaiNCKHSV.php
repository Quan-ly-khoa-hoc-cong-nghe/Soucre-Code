<?php
class DeTaiNCKHSV
{
    private $conn;
    private $table_name = "DeTaiNCKHSV";  // Đảm bảo tên bảng chính xác

    public $maDeTaiSV;
    public $tenDeTai;
    public $moTa;
    public $trangThai;
    public $fileHopDong;
    public $maHoSo;

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

    public function add()
    {
        try {
            // Sinh mã đề tài tự động
            $this->maDeTaiSV = $this->generateMaDeTaiSV();  // Gọi hàm tự sinh mã

            // Câu truy vấn SQL để thêm dữ liệu vào bảng DeTaiNCKHSV
            $query = "INSERT INTO " . $this->table_name . " 
              (MaDeTaiSV, TenDeTai, MoTa, TrangThai, FileHopDong, MaHoSo) 
              VALUES (:maDeTaiSV, :tenDeTai, :moTa, :trangThai, :fileHopDong, :maHoSo)";

            $stmt = $this->conn->prepare($query);

            // Bind tham số
            $stmt->bindParam(':maDeTaiSV', $this->maDeTaiSV);  // Đưa mã đề tài vào bindParam
            $stmt->bindParam(':tenDeTai', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);

            // Thực thi câu lệnh và kiểm tra kết quả
            if ($stmt->execute()) {
                // Lấy mã đề tài từ trường MaDeTaiSV vừa thêm
                return $this->maDeTaiSV;  // Trả về mã đề tài tự sinh sau khi thêm thành công
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Lỗi khi thêm đề tài: " . implode(", ", $errorInfo));
                return false;
            }
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
                        FileHopDong = :fileHopDong, MaHoSo = :maHoSo 
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':maDeTaiSV', $this->maDeTaiSV);
            $stmt->bindParam(':tenDeTai', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);

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
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaDeTaiSV = :maDeTaiSV";
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
