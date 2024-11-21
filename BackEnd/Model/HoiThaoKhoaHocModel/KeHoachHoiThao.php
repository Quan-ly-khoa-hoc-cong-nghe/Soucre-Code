<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
use Google\Client;
use Google\Service\Drive;

class KeHoachHoiThao {
    private $conn;
    private $table = "KeHoachHoiThao";

    public $ma_hoi_thao;
    public $ten_hoi_thao;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $kinh_phi;
    public $file_ke_hoach; // URL file trên Google Drive
    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một kế hoạch
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_hoi_thao = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_hoi_thao);
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

    // Thêm kế hoạch và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            $this->file_ke_hoach = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ma_hoi_thao=?, ten_hoi_thao=?, ngay_bat_dau=?, ngay_ket_thuc=?, kinh_phi=?, file_ke_hoach=?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->ma_hoi_thao);
            $stmt->bindParam(2, $this->ten_hoi_thao);
            $stmt->bindParam(3, $this->ngay_bat_dau);
            $stmt->bindParam(4, $this->ngay_ket_thuc);
            $stmt->bindParam(5, $this->kinh_phi);
            $stmt->bindParam(6, $this->file_ke_hoach);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm kế hoạch: " . $e->getMessage());
        }
    }

    // Cập nhật kế hoạch và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_ke_hoach'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->file_ke_hoach = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET ten_hoi_thao=?, ngay_bat_dau=?, ngay_ket_thuc=?, kinh_phi=?, file_ke_hoach=? WHERE ma_hoi_thao=?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->ten_hoi_thao);
            $stmt->bindParam(2, $this->ngay_bat_dau);
            $stmt->bindParam(3, $this->ngay_ket_thuc);
            $stmt->bindParam(4, $this->kinh_phi);
            $stmt->bindParam(5, $this->file_ke_hoach);
            $stmt->bindParam(6, $this->ma_hoi_thao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật kế hoạch: " . $e->getMessage());
        }
    }

    // Xóa kế hoạch và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['file_ke_hoach'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE ma_hoi_thao = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_hoi_thao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa kế hoạch: " . $e->getMessage());
        }
    }
}
?>
