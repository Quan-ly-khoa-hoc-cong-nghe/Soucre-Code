<?php
class TaiLieu {
    private $conn;
    private $table = "TaiLieu";

    public $MaTaiLieu;
    public $TenTaiLieu;
    public $LoaiTaiLieu;
    public $DuongDanFile;
    public $ThoiGianTao;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả tài liệu
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới tài liệu
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaTaiLieu=:MaTaiLieu, TenTaiLieu=:TenTaiLieu, LoaiTaiLieu=:LoaiTaiLieu, DuongDanFile=:DuongDanFile, ThoiGianTao=:ThoiGianTao, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);
        $stmt->bindParam(":TenTaiLieu", $this->TenTaiLieu);
        $stmt->bindParam(":LoaiTaiLieu", $this->LoaiTaiLieu);
        $stmt->bindParam(":DuongDanFile", $this->DuongDanFile);
        $stmt->bindParam(":ThoiGianTao", $this->ThoiGianTao);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật tài liệu
    public function update() {
        $query = "UPDATE " . $this->table . " SET TenTaiLieu=:TenTaiLieu, LoaiTaiLieu=:LoaiTaiLieu, DuongDanFile=:DuongDanFile, ThoiGianTao=:ThoiGianTao, MaHoiThao=:MaHoiThao WHERE MaTaiLieu=:MaTaiLieu";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);
        $stmt->bindParam(":TenTaiLieu", $this->TenTaiLieu);
        $stmt->bindParam(":LoaiTaiLieu", $this->LoaiTaiLieu);
        $stmt->bindParam(":DuongDanFile", $this->DuongDanFile);
        $stmt->bindParam(":ThoiGianTao", $this->ThoiGianTao);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa tài liệu
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaTaiLieu=:MaTaiLieu";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaTaiLieu", $this->MaTaiLieu);

        return $stmt->execute();
    }
}
?>
