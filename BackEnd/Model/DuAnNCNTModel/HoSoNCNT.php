<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');

use Google\Client;
use Google\Service\Drive;

class HoSoNCNT {
    private $conn;
    private $table = "HoSoNCNT";

    public $ma_ho_so;
    public $ngay_nop;
    public $file_ho_so; // URL file trên Google Drive
    public $trang_thai;
    public $ma_dat_hang;
    public $ma_khoa;

    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả hồ sơ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một hồ sơ
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_ho_so = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Upload file lên Google Drive
    private function uploadFileToGoogleDrive($filePath, $fileName) {
        try {
            $fileMetadata = new Drive\DriveFile([
                'name' => $fileName,
                'parents' => ['YOUR_FOLDER_ID'] // Thay YOUR_FOLDER_ID bằng ID thư mục Google Drive
            ]);

            $fileContent = file_get_contents($filePath);

            $uploadedFile = $this->driveService->files->create($fileMetadata, [
                'data' => $fileContent,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            $fileId = $uploadedFile->id;

            // Thiết lập quyền công khai
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->driveService->permissions->create($fileId, $permission);

            // Tạo link công khai
            $fileUrl = "https://drive.google.com/file/d/$fileId/view";
            return ['fileId' => $fileId, 'fileUrl' => $fileUrl];
        } catch (Exception $e) {
            throw new Exception("Lỗi upload file: " . $e->getMessage());
        }
    }

    // Xóa file trên Google Drive
    private function deleteFileFromGoogleDrive($fileId) {
        try {
            $this->driveService->files->delete($fileId);
            return true;
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa file Google Drive: " . $e->getMessage());
        }
    }

    // Thêm hồ sơ và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->file_ho_so = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ma_ho_so=?, ngay_nop=?, file_ho_so=?, trang_thai=?, ma_dat_hang=?, ma_khoa=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_ho_so);
            $stmt->bindParam(2, $this->ngay_nop);
            $stmt->bindParam(3, $this->file_ho_so);
            $stmt->bindParam(4, $this->trang_thai);
            $stmt->bindParam(5, $this->ma_dat_hang);
            $stmt->bindParam(6, $this->ma_khoa);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm hồ sơ: " . $e->getMessage());
        }
    }

    // Cập nhật hồ sơ và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_ho_so'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->file_ho_so = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET ngay_nop=?, file_ho_so=?, trang_thai=?, ma_dat_hang=?, ma_khoa=? WHERE ma_ho_so=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ngay_nop);
            $stmt->bindParam(2, $this->file_ho_so);
            $stmt->bindParam(3, $this->trang_thai);
            $stmt->bindParam(4, $this->ma_dat_hang);
            $stmt->bindParam(5, $this->ma_khoa);
            $stmt->bindParam(6, $this->ma_ho_so);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật hồ sơ: " . $e->getMessage());
        }
    }

    // Xóa hồ sơ và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_ho_so'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE ma_ho_so = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_ho_so);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa hồ sơ: " . $e->getMessage());
        }
    }
}
?>
