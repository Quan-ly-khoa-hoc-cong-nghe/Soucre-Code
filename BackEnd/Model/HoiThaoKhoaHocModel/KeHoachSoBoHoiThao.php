<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
use Google\Client;
use Google\Service\Drive;

class KeHoachSoBo {
    private $conn;
    private $table = "KeHoachSoBoHoiThao";

    public $MaKeHoachSoBo;
    public $NgayGui;
    public $FileKeHoach; // URL file trên Google Drive
    public $TrangThai;
    public $MaKhoa;
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
        $query = "SELECT * FROM " . $this->table . " WHERE MaKeHoachSoBo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaKeHoachSoBo);
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

    // Thêm mới kế hoạch và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            $this->FileKeHoach = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET MaKeHoachSoBo=:MaKeHoachSoBo, NgayGui=:NgayGui, FileKeHoach=:FileKeHoach, TrangThai=:TrangThai, MaKhoa=:MaKhoa";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);
            $stmt->bindParam(":NgayGui", $this->NgayGui);
            $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
            $stmt->bindParam(":TrangThai", $this->TrangThai);
            $stmt->bindParam(":MaKhoa", $this->MaKhoa);

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
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['FileKeHoach'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->FileKeHoach = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET NgayGui=:NgayGui, FileKeHoach=:FileKeHoach, TrangThai=:TrangThai, MaKhoa=:MaKhoa WHERE MaKeHoachSoBo=:MaKeHoachSoBo";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);
            $stmt->bindParam(":NgayGui", $this->NgayGui);
            $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
            $stmt->bindParam(":TrangThai", $this->TrangThai);
            $stmt->bindParam(":MaKhoa", $this->MaKhoa);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật kế hoạch: " . $e->getMessage());
        }
    }

    // Xóa kế hoạch và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['FileKeHoach'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE MaKeHoachSoBo = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->MaKeHoachSoBo);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa kế hoạch: " . $e->getMessage());
        }
    }
}
?>
