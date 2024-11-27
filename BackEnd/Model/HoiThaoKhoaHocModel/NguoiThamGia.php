<?php
class NguoiThamGia {
    private $conn;
    private $table = "NguoiThamGia";

    public $MaNguoiThamGia;
    public $TenNguoiThamGia;
    public $Sdt;
    public $Email;
    public $HocHam;
    public $HocVi;
    public $FileHoSo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức sinh mã người tham gia tự động
    private function generateMaNguoiThamGia() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Tạo mã người tham gia mới theo định dạng TGHTKH + (count + 1)
        return "TGHTKH" . ($count + 1);
    }

    // Lấy tất cả người tham gia
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới người tham gia
    public function add() {
        try {
            // Tạo mã người tham gia tự động
            $this->MaNguoiThamGia = $this->generateMaNguoiThamGia();

            $query = "INSERT INTO " . $this->table . " 
                        (MaNguoiThamGia, TenNguoiThamGia, Sdt, Email, HocHam, HocVi, FileHoSo) 
                        VALUES (:MaNguoiThamGia, :TenNguoiThamGia, :Sdt, :Email, :HocHam, :HocVi, :FileHoSo)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
            $stmt->bindParam(":TenNguoiThamGia", $this->TenNguoiThamGia);
            $stmt->bindParam(":Sdt", $this->Sdt);
            $stmt->bindParam(":Email", $this->Email);
            $stmt->bindParam(":HocHam", $this->HocHam);
            $stmt->bindParam(":HocVi", $this->HocVi);
            $stmt->bindParam(":FileHoSo", $this->FileHoSo);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật thông tin người tham gia
    public function update() {
        $query = "UPDATE " . $this->table . " 
                    SET TenNguoiThamGia = :TenNguoiThamGia, Sdt = :Sdt, Email = :Email, 
                        HocHam = :HocHam, HocVi = :HocVi, FileHoSo = :FileHoSo 
                    WHERE MaNguoiThamGia = :MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);
        $stmt->bindParam(":TenNguoiThamGia", $this->TenNguoiThamGia);
        $stmt->bindParam(":Sdt", $this->Sdt);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":HocHam", $this->HocHam);
        $stmt->bindParam(":HocVi", $this->HocVi);
        $stmt->bindParam(":FileHoSo", $this->FileHoSo);

        return $stmt->execute();
    }

    // Xóa người tham gia
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaNguoiThamGia = :MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

        return $stmt->execute();
    }
}
?>
