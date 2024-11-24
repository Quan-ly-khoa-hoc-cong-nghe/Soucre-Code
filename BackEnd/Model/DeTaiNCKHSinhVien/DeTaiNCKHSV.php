<?php
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class DeTaiNCKHSV {

    private $db;
    private $driveService;
    public $ma_de_tai;
    public $ten_de_tai;
    public $mo_ta;
    public $trang_thai;
    public $file_hop_dong;
    public $ma_ho_so;
    public $ma_nhom;

    public function __construct($pdo, $accessToken)
    {
        $this->db = $pdo;

        // Khởi tạo Google Client
        $client = new Google\Client();
        $client->setAccessToken($accessToken);
        $this->driveService = new Google\Service\Drive($client);
    }

    // Lấy tất cả đề tài
    public function getAllDeTai()
    {
        $stmt = $this->db->prepare("SELECT * FROM DeTaiNCKHSV");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một đề tài theo mã
    public function getDeTaiByMa($maDeTai)
    {
        $stmt = $this->db->prepare("SELECT * FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $maDeTai);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đề tài mới
    public function addDeTai()
    {
        // Lấy dữ liệu từ request
        $this->ma_de_tai = $_POST['ma_de_tai'];
        $this->ten_de_tai = $_POST['ten_de_tai'];
        $this->mo_ta = $_POST['mo_ta'];
        $this->trang_thai = $_POST['trang_thai'];
        $this->file_hop_dong = $_FILES['file_hop_dong']['tmp_name'] ?? null;
        $this->ma_ho_so = $_POST['ma_ho_so'];
        $this->ma_nhom = $_POST['ma_nhom'];

        if ($this->checkMaDeTaiExists()) {
            return "Mã đề tài đã tồn tại.";
        }

        // Upload file lên Google Drive và lấy URL
        $fileUrl = $this->uploadFileToGoogleDrive($this->file_hop_dong);

        $stmt = $this->db->prepare("INSERT INTO DeTaiNCKHSV (MaDeTaiSV, TenDeTai, MoTa, TrangThai, FileHopDong, MaHoSo, MaNhomNCKHSV) 
                                    VALUES (:maDeTai, :tenDeTai, :moTa, :trangThai, :fileHopDong, :maHoSo, :maNhom)");
        $stmt->bindParam(':maDeTai', $this->ma_de_tai);
        $stmt->bindParam(':tenDeTai', $this->ten_de_tai);
        $stmt->bindParam(':moTa', $this->mo_ta);
        $stmt->bindParam(':trangThai', $this->trang_thai);
        $stmt->bindParam(':fileHopDong', $fileUrl);
        $stmt->bindParam(':maHoSo', $this->ma_ho_so);
        $stmt->bindParam(':maNhom', $this->ma_nhom);

        return $stmt->execute();
    }

    // Kiểm tra mã đề tài có tồn tại không
    public function checkMaDeTaiExists()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $this->ma_de_tai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Cập nhật đề tài
    public function updateDeTai()
    {
        // Lấy dữ liệu từ request
        $this->ma_de_tai = $_POST['ma_de_tai'];
        $this->ten_de_tai = $_POST['ten_de_tai'];
        $this->mo_ta = $_POST['mo_ta'];
        $this->trang_thai = $_POST['trang_thai'];
        $this->file_hop_dong = $_FILES['file_hop_dong']['tmp_name'] ?? null;
        $this->ma_ho_so = $_POST['ma_ho_so'];
        $this->ma_nhom = $_POST['ma_nhom'];

        if (!$this->checkMaDeTaiExists()) {
            return "Mã đề tài không tồn tại.";
        }

        if ($this->file_hop_dong) {
            // Upload file mới và lấy URL
            $fileUrl = $this->uploadFileToGoogleDrive($this->file_hop_dong);
            $this->file_hop_dong = $fileUrl;  // Cập nhật lại URL của file
        }

        $stmt = $this->db->prepare("UPDATE DeTaiNCKHSV 
                                    SET TenDeTai = :tenDeTai, MoTa = :moTa, TrangThai = :trangThai, FileHopDong = :fileHopDong, MaHoSo = :maHoSo, MaNhomNCKHSV = :maNhom 
                                    WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $this->ma_de_tai);
        $stmt->bindParam(':tenDeTai', $this->ten_de_tai);
        $stmt->bindParam(':moTa', $this->mo_ta);
        $stmt->bindParam(':trangThai', $this->trang_thai);
        $stmt->bindParam(':fileHopDong', $this->file_hop_dong); // Cập nhật URL của file
        $stmt->bindParam(':maHoSo', $this->ma_ho_so);
        $stmt->bindParam(':maNhom', $this->ma_nhom);

        return $stmt->execute();
    }

    // Xóa đề tài
    public function deleteDeTai()
    {
        if (!$this->checkMaDeTaiExists()) {
            return "Mã đề tài không tồn tại.";
        }

        $stmt = $this->db->prepare("DELETE FROM DeTaiNCKHSV WHERE MaDeTaiSV = :maDeTai");
        $stmt->bindParam(':maDeTai', $this->ma_de_tai);
        return $stmt->execute();
    }

    // Upload file lên Google Drive và trả về URL
    private function uploadFileToGoogleDrive($filePath)
    {
        try {
            $fileMetadata = new Drive\DriveFile([
                'name' => basename($filePath),
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

            // Cấp quyền công khai cho file
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->driveService->permissions->create($fileId, $permission);

            // Tạo đường dẫn công khai cho file
            $fileUrl = "https://drive.google.com/file/d/$fileId/view";
            return $fileUrl;
        } catch (Exception $e) {
            throw new Exception("Lỗi khi tải file lên Google Drive: " . $e->getMessage());
        }
    }
}
?>
