<?php
class KeHoachDTCS {
    private $conn;
    private $table = "KeHoachDTCS";

    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $kinh_phi;
    public $file_ke_hoach; // URL file trên Google Drive
    public $ma_dtcs;

    private $accessToken; // Access Token của Google Drive
    private $refreshToken; // Refresh Token
    private $clientId;
    private $clientSecret;

    public function __construct($db, $accessToken, $refreshToken, $clientId, $clientSecret) {
        $this->conn = $db;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    // Làm mới Access Token khi hết hạn
    private function refreshAccessToken() {
        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Lỗi làm mới Access Token: " . $error);
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['access_token'])) {
            $this->accessToken = $responseData['access_token'];
            return $this->accessToken;
        }

        throw new Exception("Không thể làm mới Access Token.");
    }

    // Upload file lên Google Drive
    private function uploadFileToGoogleDrive($filePath, $fileName) {
        if (!$this->accessToken) {
            $this->refreshAccessToken();
        }

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
            "Content-Length: " . strlen($body),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Lỗi upload: " . $error);
        }

        $responseData = json_decode($response, true);
        if (isset($responseData['id'])) {
            $fileId = $responseData['id'];
            $fileUrl = "https://drive.google.com/file/d/$fileId/view";
            return ['fileId' => $fileId, 'fileUrl' => $fileUrl];
        }

        throw new Exception("Không thể upload file lên Google Drive.");
    }

    // Xóa file trên Google Drive
    private function deleteFileFromGoogleDrive($fileId) {
        if (!$this->accessToken) {
            $this->refreshAccessToken();
        }

        $url = "https://www.googleapis.com/drive/v3/files/$fileId";

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Lỗi xóa file trên Google Drive: " . $error);
        }

        return true;
    }

    // Lấy danh sách tất cả kế hoạch
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy thông tin chi tiết một kế hoạch
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_dtcs = :ma_dtcs";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ma_dtcs", $this->ma_dtcs);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm kế hoạch (có upload file)
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->file_ke_hoach = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ngay_bat_dau=:ngay_bat_dau, ngay_ket_thuc=:ngay_ket_thuc, kinh_phi=:kinh_phi, file_ke_hoach=:file_ke_hoach, ma_dtcs=:ma_dtcs";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":ngay_bat_dau", $this->ngay_bat_dau);
            $stmt->bindParam(":ngay_ket_thuc", $this->ngay_ket_thuc);
            $stmt->bindParam(":kinh_phi", $this->kinh_phi);
            $stmt->bindParam(":file_ke_hoach", $this->file_ke_hoach);
            $stmt->bindParam(":ma_dtcs", $this->ma_dtcs);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật kế hoạch (có cập nhật file)
    public function update($filePath = null, $fileName = null) {
        if ($filePath && $fileName) {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);
            $this->file_ke_hoach = $uploadResult['fileUrl'];
        }

        $query = "UPDATE " . $this->table . " SET ngay_bat_dau=:ngay_bat_dau, ngay_ket_thuc=:ngay_ket_thuc, kinh_phi=:kinh_phi, file_ke_hoach=:file_ke_hoach WHERE ma_dtcs=:ma_dtcs";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":ngay_bat_dau", $this->ngay_bat_dau);
        $stmt->bindParam(":ngay_ket_thuc", $this->ngay_ket_thuc);
        $stmt->bindParam(":kinh_phi", $this->kinh_phi);
        $stmt->bindParam(":file_ke_hoach", $this->file_ke_hoach);
        $stmt->bindParam(":ma_dtcs", $this->ma_dtcs);

        return $stmt->execute();
    }

    // Xóa kế hoạch và file trên Google Drive
    public function delete() {
        preg_match('/\/d\/(.*?)\/view/', $this->file_ke_hoach, $matches);
        $fileId = $matches[1] ?? null;

        if ($fileId) {
            $this->deleteFileFromGoogleDrive($fileId);
        }

        $query = "DELETE FROM " . $this->table . " WHERE ma_dtcs=:ma_dtcs";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ma_dtcs", $this->ma_dtcs);

        return $stmt->execute();
    }
}
?>
