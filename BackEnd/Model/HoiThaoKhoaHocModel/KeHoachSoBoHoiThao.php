<?php
class KeHoachSoBoHoiThao {
    private $conn;
    private $table = "KeHoachSoBoHoiThao";

    public $MaKeHoachSoBo;
    public $NgayGui;
    public $FileKeHoach;
    public $TrangThai;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Phương thức sinh mã kế hoạch sơ bộ hội thảo tự động
    private function generateMaKeHoachSoBo() {
        // Đếm số dòng hiện tại trong bảng
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Tạo mã kế hoạch sơ bộ hội thảo mới theo định dạng KHSBHTKH + (count + 1)
        return "KHSBHTKH" . ($count + 1);
    }

    // Lấy tất cả kế hoạch sơ bộ hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới kế hoạch sơ bộ hội thảo
    public function add() {
        try {
            // Tạo mã kế hoạch sơ bộ hội thảo tự động
            $this->MaKeHoachSoBo = $this->generateMaKeHoachSoBo();

            $query = "INSERT INTO " . $this->table . " 
                        (MaKeHoachSoBo, NgayGui, FileKeHoach, TrangThai, MaKhoa) 
                        VALUES (:MaKeHoachSoBo, :NgayGui, :FileKeHoach, :TrangThai, :MaKhoa)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);
            $stmt->bindParam(":NgayGui", $this->NgayGui);
            $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
            $stmt->bindParam(":TrangThai", $this->TrangThai);
            $stmt->bindParam(":MaKhoa", $this->MaKhoa);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật kế hoạch sơ bộ hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " 
                    SET NgayGui=:NgayGui, FileKeHoach=:FileKeHoach, TrangThai=:TrangThai, MaKhoa=:MaKhoa 
                    WHERE MaKeHoachSoBo=:MaKeHoachSoBo";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);
        $stmt->bindParam(":NgayGui", $this->NgayGui);
        $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        return $stmt->execute();
    }

    // Xóa kế hoạch sơ bộ hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaKeHoachSoBo=:MaKeHoachSoBo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);

        return $stmt->execute();
    }
}
?>
