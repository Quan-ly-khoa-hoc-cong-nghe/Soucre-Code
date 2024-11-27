<?php
class PhienHoiThao {
    private $conn;
    private $table = "PhienHoiThao";
    private $hoithao_table = "HoiThao"; // Bảng HoiThao để lấy MaHoiThao

    public $MaPhienHoiThao;
    public $TenPhienHoiThao;
    public $ThoiGianBatDau;
    public $ThoiGianKetThuc;
    public $MoTa;
    public $MaHoiThao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức sinh mã phiên hội thảo tự động
    private function generateMaPhienHoiThao() {
        // Lấy MaHoiThao từ bảng HoiThao
        $query = "SELECT MaHoiThao FROM " . $this->hoithao_table . " WHERE MaHoiThao = :MaHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu tìm thấy MaHoiThao, tiếp tục sinh MaPhienHoiThao
        if ($result) {
            $maHoiThao = $result['MaHoiThao'];
            // Đếm số lượng phiên hội thảo trong bảng PhienHoiThao
            $queryCount = "SELECT COUNT(*) FROM " . $this->table . " WHERE MaHoiThao = :MaHoiThao";
            $stmtCount = $this->conn->prepare($queryCount);
            $stmtCount->bindParam(":MaHoiThao", $this->MaHoiThao);
            $stmtCount->execute();
            $count = $stmtCount->fetchColumn();

            // Sinh MaPhienHoiThao theo định dạng MaHoiThao + "Phien" + (count + 1)
            return $maHoiThao . "Phien" . ($count + 1);
        }

        return null; // Nếu không tìm thấy MaHoiThao trong bảng HoiThao
    }

    // Lấy tất cả phiên hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới phiên hội thảo
    public function add() {
        // Tạo mã phiên hội thảo tự động
        $this->MaPhienHoiThao = $this->generateMaPhienHoiThao();

        $query = "INSERT INTO " . $this->table . " SET MaPhienHoiThao=:MaPhienHoiThao, TenPhienHoiThao=:TenPhienHoiThao, ThoiGianBatDau=:ThoiGianBatDau, ThoiGianKetThuc=:ThoiGianKetThuc, MoTa=:MoTa, MaHoiThao=:MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":TenPhienHoiThao", $this->TenPhienHoiThao);
        $stmt->bindParam(":ThoiGianBatDau", $this->ThoiGianBatDau);
        $stmt->bindParam(":ThoiGianKetThuc", $this->ThoiGianKetThuc);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật phiên hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " SET TenPhienHoiThao=:TenPhienHoiThao, ThoiGianBatDau=:ThoiGianBatDau, ThoiGianKetThuc=:ThoiGianKetThuc, MoTa=:MoTa, MaHoiThao=:MaHoiThao WHERE MaPhienHoiThao=:MaPhienHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);
        $stmt->bindParam(":TenPhienHoiThao", $this->TenPhienHoiThao);
        $stmt->bindParam(":ThoiGianBatDau", $this->ThoiGianBatDau);
        $stmt->bindParam(":ThoiGianKetThuc", $this->ThoiGianKetThuc);
        $stmt->bindParam(":MoTa", $this->MoTa);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Xóa phiên hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaPhienHoiThao=:MaPhienHoiThao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaPhienHoiThao", $this->MaPhienHoiThao);

        return $stmt->execute();
    }
}
?>
