<?php

class HoSoDTCS {
    private $conn;
    private $table = "HoSoDTCS";

    public $ma_ho_so;
    public $ngay_nop;
    public $file_ho_so; // Đây sẽ là URL của file trên Google Drive
    public $trang_thai;
    public $ma_khoa;
    private $accessToken;

    public function __construct($db, $accessToken) {
        $this->conn = $db;
        $this->accessToken = $accessToken; // Access Token của Google Drive
    }

    // Lấy tất cả hồ sơ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
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
        $url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';

        $fileMetadata = ['name' => $fileName];
        $fileData = file_get_contents($filePath);

        $boundary = uniqid();
        $delimiter = "--" . $boundary;
        $eol = "\r\n";
        $body = '';

        $body .= $delimiter . $eol;
        $body .= 'Content-Type: application/json; charset=UTF-8' . $eol . $eol;
        $body .= json_encode($fileMetadata) . $eol;

        $body .= $delimiter . $eol;
        $body .= 'Content-Type: ' . mime_content_type($filePath) . $eol . $eol;
        $body .= $fileData . $eol;
        $body .= $delimiter . "--";

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: multipart/related; boundary={$boundary}",
            "Content-Length: " . strlen($body)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            echo "Lỗi upload: " . $error;
            return null;
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['id'])) {
            $fileId = $responseData['id'];
            $fileUrl = "https://drive.google.com/file/d/$fileId/view";
            return ['fileId' => $fileId, 'fileUrl' => $fileUrl];
        }

        return null;
    }

    // Xóa file trên Google Drive
    private function deleteFileFromGoogleDrive($fileId) {
        $url = "https://www.googleapis.com/drive/v3/files/$fileId";

        $headers = [
            "Authorization: Bearer {$this->accessToken}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            echo "Lỗi xóa file: " . $error;
            return false;
        }

        return true;
    }

    // Thêm hồ sơ và upload file lên Google Drive
    public function add($filePath, $fileName) {
        $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

        if (!$uploadResult) {
            return false;
        }

        $this->file_ho_so = $uploadResult['fileUrl'];
        $fileId = $uploadResult['fileId'];

        $query = "INSERT INTO " . $this->table . " SET ma_ho_so=?, ngay_nop=?, file_ho_so=?, trang_thai=?, ma_khoa=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);
        $stmt->bindParam(2, $this->ngay_nop);
        $stmt->bindParam(3, $this->file_ho_so);
        $stmt->bindParam(4, $this->trang_thai);
        $stmt->bindParam(5, $this->ma_khoa);

        return $stmt->execute();
    }

    // Xóa hồ sơ và file trên Google Drive
    public function delete() {
        // Lấy thông tin hồ sơ trước khi xóa
        $hoSo = $this->getOne();
        if (!$hoSo) {
            return false;
        }

        // Lấy fileId từ URL
        preg_match('/\/d\/(.*?)\/view/', $hoSo['file_ho_so'], $matches);
        $fileId = $matches[1] ?? null;

        // Xóa file trên Google Drive
        if ($fileId && !$this->deleteFileFromGoogleDrive($fileId)) {
            echo "Không thể xóa file trên Google Drive.";
            return false;
        }

        // Xóa hồ sơ trong database
        $query = "DELETE FROM " . $this->table . " WHERE ma_ho_so = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_ho_so);

        return $stmt->execute();
    }

    // Cập nhật hồ sơ và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        // Nếu có file mới, upload lên Google Drive
        if ($filePath && $fileName) {
            // Lấy thông tin hồ sơ hiện tại
            $hoSo = $this->getOne();
            if (!$hoSo) {
                return false;
            }
    
            // Lấy fileId từ URL file cũ
            preg_match('/\/d\/(.*?)\/view/', $hoSo['file_ho_so'], $matches);
            $oldFileId = $matches[1] ?? null;
    
            // Upload file mới lên Google Drive
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            if (!$uploadResult) {
                echo "Không thể upload file mới lên Google Drive.";
                return false;
            }
    
            // Lấy URL và fileId mới
            $this->file_ho_so = $uploadResult['fileUrl'];
    
            // Xóa file cũ trên Google Drive
            if ($oldFileId && !$this->deleteFileFromGoogleDrive($oldFileId)) {
                echo "Không thể xóa file cũ trên Google Drive.";
            }
        }
    
        // Cập nhật thông tin hồ sơ trong database
        $query = "UPDATE " . $this->table . " SET ngay_nop=?, file_ho_so=?, trang_thai=?, ma_khoa=? WHERE ma_ho_so=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_nop);
        $stmt->bindParam(2, $this->file_ho_so);
        $stmt->bindParam(3, $this->trang_thai);
        $stmt->bindParam(4, $this->ma_khoa);
        $stmt->bindParam(5, $this->ma_ho_so); // Đảm bảo bind `ma_ho_so` cuối cùng
    
        return $stmt->execute();
    }
}
?>
