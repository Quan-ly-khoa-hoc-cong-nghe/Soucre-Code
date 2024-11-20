<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
// Đường dẫn tới thư viện Google API Client
use Google\Client;
use Google\Service\Drive;

class TaiLieu {
    private $conn;
    private $table = "TaiLieu";

    public $MaTaiLieu;
    public $TenTaiLieu;
    public $LoaiTaiLieu;
    public $DuongDanFile; // URL file trên Google Drive
    public $ThoiGianTao;
    public $MaHoiThao;

    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả tài liệu
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một tài liệu
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaTaiLieu = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaTaiLieu);
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

    // Thêm mới tài liệu và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->DuongDanFile = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET MaTaiLieu=:MaTaiLieu, TenTaiLieu=:TenTaiLieu, LoaiTaiLieu=:LoaiTaiLieu, DuongDanFile=:DuongDanFile, ThoiGianTao=:ThoiGianTao, MaHoiThao=:MaHoiThao";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);
            $stmt->bindParam(":TenTaiLieu", $this->TenTaiLieu);
            $stmt->bindParam(":LoaiTaiLieu", $this->LoaiTaiLieu);
            $stmt->bindParam(":DuongDanFile", $this->DuongDanFile);
            $stmt->bindParam(":ThoiGianTao", $this->ThoiGianTao);
            $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm tài liệu: " . $e->getMessage());
        }
    }

    // Cập nhật tài liệu và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['DuongDanFile'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->DuongDanFile = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET TenTaiLieu=:TenTaiLieu, LoaiTaiLieu=:LoaiTaiLieu, DuongDanFile=:DuongDanFile, ThoiGianTao=:ThoiGianTao, MaHoiThao=:MaHoiThao WHERE MaTaiLieu=:MaTaiLieu";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);
            $stmt->bindParam(":TenTaiLieu", $this->TenTaiLieu);
            $stmt->bindParam(":LoaiTaiLieu", $this->LoaiTaiLieu);
            $stmt->bindParam(":DuongDanFile", $this->DuongDanFile);
            $stmt->bindParam(":ThoiGianTao", $this->ThoiGianTao);
            $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật tài liệu: " . $e->getMessage());
        }
    }

    // Xóa tài liệu và file trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['DuongDanFile'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE MaTaiLieu=:MaTaiLieu";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa tài liệu: " . $e->getMessage());
        }
    }
}
?>
