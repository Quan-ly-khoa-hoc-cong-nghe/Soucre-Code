<?php
class TaiTro {
    private $conn;
    private $table = "TaiTro";

    public $MaTaiTro;
    public $TenTaiTro;
    public $LoaiTaiTro;
    public $SoTien;
    public $LienHe;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả tài trợ
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới tài trợ
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaTaiTro=:MaTaiTro, TenTaiTro=:TenTaiTro, LoaiTaiTro=:LoaiTaiTro, SoTien=:SoTien, LienHe=:LienHe, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTaiTro", $this->MaTaiTro);
        $stmt->bindParam(":TenTaiTro", $this->TenTaiTro);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);
        $stmt->bindParam(":LienHe", $this->LienHe);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật tài trợ
    public function update() {
        $query = "UPDATE " . $this->table . " SET TenTaiTro=:TenTaiTro, LoaiTaiTro=:LoaiTaiTro, SoTien=:SoTien, LienHe=:LienHe, MaHoiThao=:MaHoiThao WHERE MaTaiTro=:MaTaiTro";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTaiTro", $this->MaTaiTro);
        $stmt->bindParam(":TenTaiTro", $this->TenTaiTro);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);
        $stmt->bindParam(":LienHe", $this->LienHe);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa tài trợ
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaTaiTro=:MaTaiTro";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaTaiTro", $this->MaTaiTro);

        return $stmt->execute();
    }
}
?>
