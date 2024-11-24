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

    // Lấy tất cả người tham gia
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới người tham gia
    public function add() {
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
    }

    // Cập nhật thông tin người tham gia
    public function update() {
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
    }

    // Xóa người tham gia
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaNguoiThamGia=:MaNguoiThamGia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNguoiThamGia", $this->MaNguoiThamGia);

        return $stmt->execute();
    }
}
?>
