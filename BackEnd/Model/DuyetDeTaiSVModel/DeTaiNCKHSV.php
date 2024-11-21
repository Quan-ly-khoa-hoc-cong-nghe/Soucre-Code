<?php
class DeTaiNCKHSV {
    private $conn;
    private $table_name = "DeTaiNCKHSV";

    // Các thuộc tính
    public $maDeTaiSV;
    public $tenDeTai;
    public $moTa;
    public $trangThai;
    public $fileHopDong;
    public $maHoSo;
    public $maNhomNCKHSV;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức lấy tất cả đề tài
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY tenDeTai ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Phương thức thêm đề tài
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (tenDeTai, moTa, trangThai, fileHopDong, maHoSo, maNhomNCKHSV) 
                    VALUES (:ten, :moTa, :trangThai, :fileHopDong, :maHoSo, :maNhomNCKHSV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ten', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->maNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Phương thức cập nhật đề tài
    public function update() {
        try {
            // Kiểm tra các giá trị có hợp lệ không
            if (empty($this->maDeTaiSV) || empty($this->tenDeTai) || empty($this->moTa)) {
                return ["error" => "Dữ liệu không đầy đủ"];
            }
    
            $sql = "UPDATE " . $this->table_name . " 
                    SET tenDeTai = :ten, 
                        moTa = :moTa, 
                        trangThai = :trangThai, 
                        fileHopDong = :fileHopDong, 
                        maHoSo = :maHoSo, 
                        maNhomNCKHSV = :maNhomNCKHSV 
                    WHERE maDeTaiSV = :id";
            $stmt = $this->conn->prepare($sql);
    
            // Gán giá trị từ thuộc tính
            $stmt->bindParam(':id', $this->maDeTaiSV);
            $stmt->bindParam(':ten', $this->tenDeTai);
            $stmt->bindParam(':moTa', $this->moTa);
            $stmt->bindParam(':trangThai', $this->trangThai);
            $stmt->bindParam(':fileHopDong', $this->fileHopDong);
            $stmt->bindParam(':maHoSo', $this->maHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->maNhomNCKHSV);
    
            if ($stmt->execute()) {
                return true; // Trả về true nếu thành công
            } else {
                return ["error" => "Không thể cập nhật đề tài"];
            }
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
    

    // Phương thức xóa đề tài
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE maDeTaiSV = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->maDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
