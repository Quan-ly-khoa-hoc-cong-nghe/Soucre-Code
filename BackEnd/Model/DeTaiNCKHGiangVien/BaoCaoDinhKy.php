<?php
class BaoCaoDinhKy {
    private $conn;
    private $table_name = "BaoCaoDinhKy";

    public $noiDungBaoCao;
    public $ngayNop;
    public $fileBaoCao;
    public $maDeTaiNCHKGV;
    

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đề tài NCKH
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một đề tài NCKH
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE maDeTaiNCHKGV = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->maDeTaiNCHKGV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới đề tài NCKH
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET NoiDungBaoCao=:noiDungBaoCao, NgayNop=:ngayNop, FileBaoCao=:fileBaoCao, MaDeTaiNCHKGV=:maDeTaiNCHKGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":NoiDungBaoCao", $this->noiDungBaoCao);
        $stmt->bindParam(":NgayNop", $this->ngayNop);
        $stmt->bindParam(":FileBaoCao", $this->fileBaoCao);
        $stmt->bindParam(":MaDeTaiNCHKGV", $this->maDeTaiNCHKGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật đề tài NCKH
    public function update() {
        $query = "UPDATE " . $this->table_name . "SET NoiDungBaoCao=:noiDungBaoCao, NgayNop=:ngayNop, FileBaoCao=:fileBaoCao, MaDeTaiNCHKGV=:maDeTaiNCHKGV";
        $stmt = $this->conn->prepare($query);

        // Gắn dữ liệu
        $stmt->bindParam(":NoiDungBaoCao", $this->noiDungBaoCao);
        $stmt->bindParam(":NgayNop", $this->ngayNop);
        $stmt->bindParam(":FileBaoCao", $this->fileBaoCao);
        $stmt->bindParam(":MaDeTaiNCHKGV", $this->maDeTaiNCHKGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa đề tài NCKH
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaDeTaiNCKHGV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->maDeTaiNCHKGV);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
