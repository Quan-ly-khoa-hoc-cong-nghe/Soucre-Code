<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Google\Client;
use Google\Service\Drive;

class NguoiThamGia {
    private $conn;
    private $table = "NguoiThamGia";

    public $MaNguoiThamGia;
    public $TenNguoiThamGia;
    public $Sdt;
    public $Email;
    public $HocHam;
    public $HocVi;
    public $FileHoSo; // URL file trên Google Drive

    private $driveService;

    public function __construct($db, $accessToken) {
        $this->conn = $db;

        // Khởi tạo Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Drive($client);
    }

    // Lấy tất cả người tham gia
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một người tham gia
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaNguoiThamGia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaNguoiThamGia);
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

    // Thêm mới người tham gia (có upload file hồ sơ)
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->FileHoSo = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET MaNguoiThamGia=:MaNguoiThamGia, TenNguoiThamGia=:TenNguoiThamGia, Sdt=:Sdt, Email=:Email, HocHam=:HocHam, HocVi=:HocVi, FileHoSo=:FileHoSo";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
            $stmt->bindParam(":TenNguoiThamGia", $this->TenNguoiThamGia);
            $stmt->bindParam(":Sdt", $this->Sdt);
            $stmt->bindParam(":Email", $this->Email);
            $stmt->bindParam(":HocHam", $this->HocHam);
            $stmt->bindParam(":HocVi", $this->HocVi);
            $stmt->bindParam(":FileHoSo", $this->FileHoSo);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi thêm người tham gia: " . $e->getMessage());
        }
    }

    // Cập nhật thông tin người tham gia (có upload file mới nếu có)
    public function update($filePath = null, $fileName = null) {
        try {
            if ($filePath && $fileName) {
                $currentRecord = $this->getOne();
                preg_match('/\/d\/(.*?)\/view/', $currentRecord['FileHoSo'], $matches);
                $oldFileId = $matches[1] ?? null;

                $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
                $this->FileHoSo = $uploadResult['fileUrl'];

                if ($oldFileId) {
                    $this->deleteFileFromGoogleDrive($oldFileId);
                }
            }

            $query = "UPDATE " . $this->table . " SET TenNguoiThamGia=:TenNguoiThamGia, Sdt=:Sdt, Email=:Email, HocHam=:HocHam, HocVi=:HocVi, FileHoSo=:FileHoSo WHERE MaNguoiThamGia=:MaNguoiThamGia";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
            $stmt->bindParam(":TenNguoiThamGia", $this->TenNguoiThamGia);
            $stmt->bindParam(":Sdt", $this->Sdt);
            $stmt->bindParam(":Email", $this->Email);
            $stmt->bindParam(":HocHam", $this->HocHam);
            $stmt->bindParam(":HocVi", $this->HocVi);
            $stmt->bindParam(":FileHoSo", $this->FileHoSo);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi cập nhật người tham gia: " . $e->getMessage());
        }
    }

    // Xóa người tham gia và file hồ sơ trên Google Drive
    public function delete() {
        try {
            $currentRecord = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $currentRecord['FileHoSo'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE MaNguoiThamGia=:MaNguoiThamGia";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Lỗi xóa người tham gia: " . $e->getMessage());
        }
    }
}
?>
