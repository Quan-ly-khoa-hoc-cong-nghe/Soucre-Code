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

    public function getDetailedInfo()
    {
        try {
            $query = "SELECT 
                        DT.*, 
                        GV.HoTenGV, 
                        DT.TrangThai
                    FROM " . $this->table_name . " DT
                    LEFT JOIN NhomNCKHSV N ON DT.MaDeTaiSV = N.MaDeTaiSV
                    LEFT JOIN GiangVienNCKHSV GVN ON N.MaNhomNCKHSV = GVN.MaNhomNCKHSV
                    LEFT JOIN GiangVien GV ON GVN.MaGV = GV.MaGV
                    WHERE GVN.VaiTro = 'Chủ nhiệm'
                    ORDER BY DT.TenDeTai ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về tất cả các đề tài cùng thông tin đi kèm
            } else {
                return ["message" => "Không có đề tài nào"];
            }
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Phương thức lấy tất cả dữ liệu từ 3 bảng dựa trên MaDeTaiSV
    public function getInfoByMaDeTaiSV($maDeTaiSV)
    {
        try {
            $query = "
                SELECT 
                    DT.MaDeTaiSV, DT.TenDeTai, DT.MoTa, DT.TrangThai, DT.FileHopDong, DT.MaHoSo,
                    KH.NgayBatDau, KH.NgayKetThuc, KH.KinhPhi, KH.FileKeHoach,
                    SP.TenSanPham, SP.NgayHoanThanh, SP.KetQua, SP.FileSanPham
                FROM " . $this->table_name . " DT
                LEFT JOIN KeHoachNCKHSV KH ON DT.MaDeTaiSV = KH.MaDeTaiSV
                LEFT JOIN SanPhamNCKHSV SP ON DT.MaDeTaiSV = SP.MaDeTaiSV
                WHERE DT.MaDeTaiSV = :maDeTaiSV
                ORDER BY DT.TenDeTai ASC
            ";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameter
            $stmt->bindParam(':maDeTaiSV', $maDeTaiSV);

            // Execute statement
            $stmt->execute();

            // Kiểm tra nếu có dữ liệu trả về
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về tất cả dữ liệu
            } else {
                return ["message" => "Không có dữ liệu cho mã đề tài này"];
            }
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
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
