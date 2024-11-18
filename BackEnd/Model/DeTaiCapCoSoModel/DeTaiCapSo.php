<?php

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
    private $accessToken;

    public function __construct($db, $accessToken) {
        $this->conn = $db;
        $this->accessToken = $accessToken;
    }

    // Lấy tất cả đề tài
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
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

    // Thêm đề tài và upload file hợp đồng lên Google Drive
    public function add($filePath, $fileName) {
        $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

        if (!$uploadResult) {
            return false;
        }

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
    }

    // Cập nhật đề tài và upload file mới lên Google Drive nếu có
    public function update($filePath = null, $fileName = null) {
        if ($filePath && $fileName) {
            $hoSo = $this->getOne();
            preg_match('/\/d\/(.*?)\/view/', $hoSo['file_hop_dong'], $matches);
            $oldFileId = $matches[1] ?? null;

            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            if (!$uploadResult) {
                return false;
            }

            $this->file_hop_dong = $uploadResult['fileUrl'];

            if ($oldFileId && !$this->deleteFileFromGoogleDrive($oldFileId)) {
                echo "Không thể xóa file cũ trên Google Drive.";
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
    }

    // Xóa đề tài và file trên Google Drive
    public function delete() {
        $hoSo = $this->getOne();
        preg_match('/\/d\/(.*?)\/view/', $hoSo['file_hop_dong'], $matches);
        $fileId = $matches[1] ?? null;

        if ($fileId && !$this->deleteFileFromGoogleDrive($fileId)) {
            echo "Không thể xóa file trên Google Drive.";
            return false;
        }

        $query = "DELETE FROM " . $this->table . " WHERE ma_dtcs = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_dtcs);

        return $stmt->execute();
    }
}
?>
