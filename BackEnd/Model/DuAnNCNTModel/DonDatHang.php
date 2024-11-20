<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
use Google\Client;
use Google\Service\Drive;

class DonDatHang {
    private $conn;
    private $table = "DonDatHang";

    public $ma_dat_hang;
    public $ngay_dat;
    public $file_dat_hang; // URL file trên Google Drive
    public $ma_doi_tac;

    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả đơn đặt hàng
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một đơn đặt hàng
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dat_hang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dat_hang);
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

    // Thêm đơn đặt hàng và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            $this->file_dat_hang = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ma_dat_hang=?, ngay_dat=?, file_dat_hang=?, ma_doi_tac=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_dat_hang);
            $stmt->bindParam(2, $this->ngay_dat);
            $stmt->bindParam(3, $this->file_dat_hang);
            $stmt->bindParam(4, $this->ma_doi_tac);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm đơn đặt hàng: " . $e->getMessage());
        }
    }

    // Cập nhật đơn đặt hàng và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_dat_hang'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->file_dat_hang = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET ngay_dat=?, file_dat_hang=?, ma_doi_tac=? WHERE ma_dat_hang=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ngay_dat);
            $stmt->bindParam(2, $this->file_dat_hang);
            $stmt->bindParam(3, $this->ma_doi_tac);
            $stmt->bindParam(4, $this->ma_dat_hang);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật đơn đặt hàng: " . $e->getMessage());
        }
    }

    // Xóa đơn đặt hàng và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_dat_hang'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE ma_dat_hang = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_dat_hang);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa đơn đặt hàng: " . $e->getMessage());
        }
    }
}
?>
