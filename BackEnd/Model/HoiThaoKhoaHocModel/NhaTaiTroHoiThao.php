<?php
class NhaTaiTroHoiThao {
    private $conn;
    private $table = "NhaTaiTroHoiThao";

    public $MaNhaTaiTro; // Khóa ngoại, liên kết với bảng NhaTaiTro
    public $MaHoiThao;   // Khóa ngoại, liên kết với bảng HoiThao
    public $LoaiTaiTro;  // Loại tài trợ
    public $SoTien;      // Số tiền tài trợ

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    // Thêm mới tài trợ hội thảo
    public function add() {
        $query = "INSERT INTO " . $this->table . " 
            (MaNhaTaiTro, MaHoiThao, LoaiTaiTro, SoTien) 
            VALUES (:MaNhaTaiTro, :MaHoiThao, :LoaiTaiTro, :SoTien)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaNhaTaiTro", $this->MaNhaTaiTro);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);

        return $stmt->execute();
    }

    // Xóa tài trợ hội thảo
    public function delete() {
        $query = "DELETE FROM " . $this->table . " 
            WHERE MaNhaTaiTro = :MaNhaTaiTro AND MaHoiThao = :MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaNhaTaiTro", $this->MaNhaTaiTro);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);

        return $stmt->execute();
    }

    // Cập nhật thông tin tài trợ
    public function update() {
        $query = "UPDATE " . $this->table . " 
            SET LoaiTaiTro = :LoaiTaiTro, SoTien = :SoTien 
            WHERE MaNhaTaiTro = :MaNhaTaiTro AND MaHoiThao = :MaHoiThao";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":MaNhaTaiTro", $this->MaNhaTaiTro);
        $stmt->bindParam(":MaHoiThao", $this->MaHoiThao);
        $stmt->bindParam(":LoaiTaiTro", $this->LoaiTaiTro);
        $stmt->bindParam(":SoTien", $this->SoTien);

        return $stmt->execute();
    }
}
?>
