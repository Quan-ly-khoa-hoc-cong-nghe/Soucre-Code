<?php
use Google\Client;
use Google\Service\Drive;

class DeTaiCapSo {
    private $conn;
    private $table = "DeTaiCapSo";

    public $ma_dtcs;
    public $ten_de_tai;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $file_hop_dong; // URL file trên Google Drive
    public $trang_thai;
    public $ma_ho_so;
    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả đề tài
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một đề tài
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);
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

    // Thêm đề tài và upload file hợp đồng lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->file_hop_dong = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ma_dtcs=?, ten_de_tai=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_dtcs);
            $stmt->bindParam(2, $this->ten_de_tai);
            $stmt->bindParam(3, $this->ngay_bat_dau);
            $stmt->bindParam(4, $this->ngay_ket_thuc);
            $stmt->bindParam(5, $this->file_hop_dong);
            $stmt->bindParam(6, $this->trang_thai);
            $stmt->bindParam(7, $this->ma_ho_so);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm đề tài: " . $e->getMessage());
        }
    }

    // Cập nhật đề tài và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_hop_dong'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->file_hop_dong = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET ten_de_tai=?, ngay_bat_dau=?, ngay_ket_thuc=?, file_hop_dong=?, trang_thai=?, ma_ho_so=? WHERE ma_dtcs=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ten_de_tai);
            $stmt->bindParam(2, $this->ngay_bat_dau);
            $stmt->bindParam(3, $this->ngay_ket_thuc);
            $stmt->bindParam(4, $this->file_hop_dong);
            $stmt->bindParam(5, $this->trang_thai);
            $stmt->bindParam(6, $this->ma_ho_so);
            $stmt->bindParam(7, $this->ma_dtcs);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật đề tài: " . $e->getMessage());
        }
    }

    // Xóa đề tài và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_hop_dong'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE ma_dtcs = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_dtcs);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa đề tài: " . $e->getMessage());
        }
    }
}
?>
