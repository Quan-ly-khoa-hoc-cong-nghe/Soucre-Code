<?php
class SanPhamNCNT {
    private $conn;
    private $table = "SanPhamNCNT";

    public $ten_san_pham;
    public $ngay_hoan_thanh;
    public $ket_qua;
    public $ma_du_an;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một sản phẩm
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ten_san_pham = ? AND ma_du_an = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ten_san_pham);
        $stmt->bindParam(2, $this->ma_du_an);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm
    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ten_san_pham=?, ngay_hoan_thanh=?, ket_qua=?, ma_du_an=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ten_san_pham);
        $stmt->bindParam(2, $this->ngay_hoan_thanh);
        $stmt->bindParam(3, $this->ket_qua);
        $stmt->bindParam(4, $this->ma_du_an);
        return $stmt->execute();
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table . " SET ngay_hoan_thanh=?, ket_qua=? WHERE ten_san_pham=? AND ma_du_an=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ngay_hoan_thanh);
        $stmt->bindParam(2, $this->ket_qua);
        $stmt->bindParam(3, $this->ten_san_pham);
        $stmt->bindParam(4, $this->ma_du_an);
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ten_san_pham = ? AND ma_du_an = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ten_san_pham);
        $stmt->bindParam(2, $this->ma_du_an);
        return $stmt->execute();
    }
}
?>
