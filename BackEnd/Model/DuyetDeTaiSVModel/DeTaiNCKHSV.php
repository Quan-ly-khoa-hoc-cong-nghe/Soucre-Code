<?php
class DeTaiNCKHSV {
    private $conn;
    private $table_name = "DeTaiNCKHSV";

    public $MaDeTaiSV;
    public $TenDeTai;
    public $MoTa;
    public $TrangThai;
    public $FileHopDong;
    public $MaHoSo;
    public $MaNhomNCKHSV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đề tài
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY TenDeTai ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm đề tài mới
    public function add() {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaDeTaiSV, TenDeTai, MoTa, TrangThai, FileHopDong, MaHoSo, MaNhomNCKHSV) 
                    VALUES (:maDeTaiSV, :tenDeTai, :moTa, :trangThai, :fileHopDong, :maHoSo, :maNhomNCKHSV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':tenDeTai', $this->TenDeTai);
            $stmt->bindParam(':moTa', $this->MoTa);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':fileHopDong', $this->FileHopDong);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật thông tin đề tài
    public function update() {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET TenDeTai = :tenDeTai, MoTa = :moTa, TrangThai = :trangThai, 
                        FileHopDong = :fileHopDong, MaHoSo = :maHoSo, MaNhomNCKHSV = :maNhomNCKHSV 
                    WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            $stmt->bindParam(':tenDeTai', $this->TenDeTai);
            $stmt->bindParam(':moTa', $this->MoTa);
            $stmt->bindParam(':trangThai', $this->TrangThai);
            $stmt->bindParam(':fileHopDong', $this->FileHopDong);
            $stmt->bindParam(':maHoSo', $this->MaHoSo);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Xóa đề tài
    public function delete() {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiSV = :maDeTaiSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $this->MaDeTaiSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
