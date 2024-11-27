<?php
class HoiThao {
    private $conn;
    private $table = "HoiThao";

    public $MaHoiThao;
    public $TenHoiThao;
    public $NgayBatDau;
    public $NgayKetThuc;
    public $DiaDiem;
    public $SoLuongThamDu;
    public $MaKeHoachSoBo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức sinh mã hội thảo tự động
    private function generateMaHoiThao() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Tạo mã hội thảo mới theo định dạng HTKH + (count + 1)
        return "HTKH" . ($count + 1);
    }

    // Lấy tất cả hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới hội thảo
    public function add() {
        try {
            // Tạo mã hội thảo tự động
            $this->MaHoiThao = $this->generateMaHoiThao();

            $query = "INSERT INTO " . $this->table . " 
                        (MaHoiThao, TenHoiThao, NgayBatDau, NgayKetThuc, DiaDiem, SoLuongThamDu, MaKeHoachSoBo) 
                        VALUES (:MaHoiThao, :TenHoiThao, :NgayBatDau, :NgayKetThuc, :DiaDiem, :SoLuongThamDu, :MaKeHoachSoBo)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
            $stmt->bindParam(":TenHoiThao", $this->TenHoiThao);
            $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
            $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
            $stmt->bindParam(":DiaDiem", $this->DiaDiem);
            $stmt->bindParam(":SoLuongThamDu", $this->SoLuongThamDu);
            $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " 
                    SET TenHoiThao = :TenHoiThao, NgayBatDau = :NgayBatDau, NgayKetThuc = :NgayKetThuc, 
                        DiaDiem = :DiaDiem, SoLuongThamDu = :SoLuongThamDu, MaKeHoachSoBo = :MaKeHoachSoBo 
                    WHERE MaHoiThao = :MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":TenHoiThao", $this->TenHoiThao);
        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);
        $stmt->bindParam(":DiaDiem", $this->DiaDiem);
        $stmt->bindParam(":SoLuongThamDu", $this->SoLuongThamDu);
        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);

        return $stmt->execute();
    }

    // Xóa hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaHoiThao = :MaHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }
}
?>
