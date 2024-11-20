<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
use Google\Client;
use Google\Service\Drive;

class KeHoachNCNT {
    private $conn;
    private $table = "KeHoachNCNT";

    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $kinh_phi;
    public $file_ke_hoach; // URL file trên Google Drive
    public $ma_du_an;
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
        return $this->conn->query($query);
    }

    // Lấy thông tin một kế hoạch
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_du_an = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_du_an);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Upload file lên Google Drive
    private function uploadFileToGoogleDrive($filePath, $fileName) {
        try {
            $fileMetadata = new Drive\DriveFile([
                'name' => $fileName,
                'parents' => ['YOUR_FOLDER_ID'] // Thay YOUR_FOLDER_ID bằng ID thư mục trên Google Drive
            ]);

            $fileContent = file_get_contents($filePath);

            $uploadedFile = $this->driveService->files->create($fileMetadata, [
                'data' => $fileContent,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            $fileId = $uploadedFile->id;

            // Cấp quyền công khai cho file
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->driveService->permissions->create($fileId, $permission);

            // Tạo đường dẫn công khai cho file
            $fileUrl = "https://drive.google.com/file/d/$fileId/view";
            return ['fileId' => $fileId, 'fileUrl' => $fileUrl];
        } catch (Exception $e) {
            throw new Exception("Lỗi khi tải file lên Google Drive: " . $e->getMessage());
        }
    }

    // Xóa file trên Google Drive
    private function deleteFileFromGoogleDrive($fileId) {
        try {
            $this->driveService->files->delete($fileId);
            return true;
        } catch (Exception $e) {
            throw new Exception("Lỗi khi xóa file trên Google Drive: " . $e->getMessage());
        }
    }

    // Thêm kế hoạch và tải file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->file_ke_hoach = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ngay_bat_dau=:ngay_bat_dau, ngay_ket_thuc=:ngay_ket_thuc, kinh_phi=:kinh_phi, file_ke_hoach=:file_ke_hoach, ma_du_an=:ma_du_an";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":ngay_bat_dau", $this->ngay_bat_dau);
            $stmt->bindParam(":ngay_ket_thuc", $this->ngay_ket_thuc);
            $stmt->bindParam(":kinh_phi", $this->kinh_phi);
            $stmt->bindParam(":file_ke_hoach", $this->file_ke_hoach);
            $stmt->bindParam(":ma_du_an", $this->ma_du_an);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật kế hoạch và thay đổi file trên Google Drive nếu cần
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

            $query = "UPDATE " . $this->table . " SET ngay_bat_dau=:ngay_bat_dau, ngay_ket_thuc=:ngay_ket_thuc, kinh_phi=:kinh_phi, file_ke_hoach=:file_ke_hoach WHERE ma_du_an=:ma_du_an";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":ngay_bat_dau", $this->ngay_bat_dau);
            $stmt->bindParam(":ngay_ket_thuc", $this->ngay_ket_thuc);
            $stmt->bindParam(":kinh_phi", $this->kinh_phi);
            $stmt->bindParam(":file_ke_hoach", $this->file_ke_hoach);
            $stmt->bindParam(":ma_du_an", $this->ma_du_an);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
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

            $query = "DELETE FROM " . $this->table . " WHERE ma_du_an = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_du_an);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
}
?>
