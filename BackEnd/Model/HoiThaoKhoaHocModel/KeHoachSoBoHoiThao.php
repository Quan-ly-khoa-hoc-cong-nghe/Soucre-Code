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

    // Lấy tất cả kế hoạch sơ bộ hội thảo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Thêm mới kế hoạch sơ bộ hội thảo
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET MaKeHoachSoBo=:MaKeHoachSoBo, NgayGui=:NgayGui, FileKeHoach=:FileKeHoach, TrangThai=:TrangThai, MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaKeHoachSoBo", $this->MaKeHoachSoBo);
        $stmt->bindParam(":NgayGui", $this->NgayGui);
        $stmt->bindParam(":FileKeHoach", $this->FileKeHoach);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        return $stmt->execute();
    }

    // Cập nhật kế hoạch sơ bộ hội thảo
    public function update() {
        $query = "UPDATE " . $this->table . " SET NgayGui=:NgayGui, FileKeHoach=:FileKeHoach, TrangThai=:TrangThai, MaKhoa=:MaKhoa WHERE MaKeHoachSoBo=:MaKeHoachSoBo";
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
