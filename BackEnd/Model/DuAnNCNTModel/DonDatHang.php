<?php
class DonDatHang {
    private $conn;
    private $table = "DonDatHang";

    public $MaDatHang;
    public $NgayDat;
    public $FileDatHang;
    public $MaDoiTac;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đơn đặt hàng
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy một đơn đặt hàng
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDatHang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDatHang);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đơn đặt hàng
    public function add() {
        $query = "INSERT INTO " . $this->table . " (MaDatHang, NgayDat, FileDatHang, MaDoiTac) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaDatHang);
        $stmt->bindParam(2, $this->NgayDat);
        $stmt->bindParam(3, $this->FileDatHang);
        $stmt->bindParam(4, $this->MaDoiTac);

        return $stmt->execute();
    }

    // Cập nhật đơn đặt hàng
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET NgayDat = ?, FileDatHang = ?, MaDoiTac = ? 
                  WHERE MaDatHang = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->NgayDat);
        $stmt->bindParam(2, $this->FileDatHang);
        $stmt->bindParam(3, $this->MaDoiTac);
        $stmt->bindParam(4, $this->MaDatHang);

        return $stmt->execute();
    }

    // Xóa đơn đặt hàng
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDatHang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDatHang);

        return $stmt->execute();
    }
}
?>
