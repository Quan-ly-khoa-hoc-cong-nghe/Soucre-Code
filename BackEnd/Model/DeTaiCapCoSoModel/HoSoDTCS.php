<?php
class HoSoDTCS {
    private $conn;
    private $table = "HoSoDTCS";

    public $ma_ho_so;
    public $ngay_nop;
    public $file_ho_so; // URL của file trên Google Drive
    public $trang_thai;
    public $ma_khoa;
    private $accessToken;
    private $refreshToken; // Refresh Token để làm mới Access Token
    private $clientId; // Client ID từ Google Cloud
    private $clientSecret; // Client Secret từ Google Cloud

    public function __construct($db, $accessToken, $refreshToken, $clientId, $clientSecret) {
        $this->conn = $db;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    // Làm mới Access Token
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

    // Kiểm tra Access Token (Nếu lỗi 401, làm mới Access Token)
    private function ensureValidAccessToken() {
        if (!$this->accessToken) {
            $this->refreshAccessToken();
        }
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
        $this->ensureValidAccessToken();

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

    // Thêm hồ sơ và upload file lên Google Drive
    public function add($filePath, $fileName) {
        try {
            $uploadResult = $this->uploadFileToGoogleDrive($filePath, $fileName);

            $this->file_ho_so = $uploadResult['fileUrl'];

            $query = "INSERT INTO " . $this->table . " SET ma_ho_so=?, ngay_nop=?, file_ho_so=?, trang_thai=?, ma_khoa=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_ho_so);
            $stmt->bindParam(2, $this->ngay_nop);
            $stmt->bindParam(3, $this->file_ho_so);
            $stmt->bindParam(4, $this->trang_thai);
            $stmt->bindParam(5, $this->ma_khoa);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    // Xóa hồ sơ và file trên Google Drive
    public function delete() {
        try {
            $hoSo = $this->getOne();
            if (!$hoSo) {
                throw new Exception("Hồ sơ không tồn tại.");
            }

            preg_match('/\/d\/(.*?)\/view/', $hoSo['file_ho_so'], $matches);
            $fileId = $matches[1] ?? null;

            if ($fileId) {
                $this->deleteFileFromGoogleDrive($fileId);
            }

            $query = "DELETE FROM " . $this->table . " WHERE ma_ho_so = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->ma_ho_so);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    private function deleteFileFromGoogleDrive($fileId) {
        $this->ensureValidAccessToken();

        $url = "https://www.googleapis.com/drive/v3/files/$fileId";

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Lỗi xóa file: " . $error);
        }

        return true;
    }
}
?>
