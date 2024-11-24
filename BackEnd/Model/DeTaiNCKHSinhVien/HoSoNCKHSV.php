<?php
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
require_once __DIR__ . '/../../config/GoogleDriveUploader.php';

class HoSoNCKHSV {
    private $conn;
    private $table_name = "HoSoNCKHSV";

    public $MaHoSo;
    public $NgayNop;
    public $FileHoSo; // URL của file trên Google Drive
    public $TrangThai;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả hồ sơ
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY NgayNop DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm mới hồ sơ
    public function add($uploadedFile) {
        try {
            // Upload file lên Google Drive
            $uploader = new GoogleDriveUploader();
            $fileUrl = $uploader->uploadFile($uploadedFile);

            if (!$fileUrl) {
                return ["error" => "Không thể upload file lên Google Drive"];
            }

            // Lưu thông tin hồ sơ vào database
            $sql = "INSERT INTO " . $this->table_name . " (MaHoSo, NgayNop, FileHoSo, TrangThai, MaKhoa) 
                    VALUES (:maHoSo, :ngayNop, :fileHoSo, :trangThai, :maKhoa)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':ngayNop', $this->NgayNop);
            $stmt->bindParam(':fileHoSo', $fileUrl);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi thêm mới: " . $e->getMessage()];
        }
    }

    // Cập nhật hồ sơ
    public function update($uploadedFile = null) {
        try {
            // Nếu có file mới, upload lên Google Drive
            $fileUrl = $this->FileHoSo;
            if ($uploadedFile) {
                $uploader = new GoogleDriveUploader();
                $fileUrl = $uploader->uploadFile($uploadedFile);

                if (!$fileUrl) {
                    return ["error" => "Không thể upload file lên Google Drive"];
                }
            }

            // Cập nhật thông tin hồ sơ trong database
            $sql = "UPDATE " . $this->table_name . " 
                    SET NgayNop = :ngayNop, FileHoSo = :fileHoSo, TrangThai = :trangThai, MaKhoa = :maKhoa 
                    WHERE MaHoSo = :maHoSo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':ngayNop', $this->NgayNop);
            $stmt->bindParam(':fileHoSo', $fileUrl);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':maKhoa', $this->MaKhoa);

            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi cập nhật: " . $e->getMessage()];
        }
    }

    // Xóa hồ sơ
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaHoSo = :maHoSo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi xóa: " . $e->getMessage()];
        }
    }
}
?>
