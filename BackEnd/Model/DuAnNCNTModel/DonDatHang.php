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

    // Lấy số tự động tăng cuối cùng trong MaDatHang
    private function generateMaDatHang() {
        // Truy vấn để lấy MaDatHang lớn nhất
        $query = "SELECT MAX(CAST(SUBSTRING(MaDatHang, 7) AS UNSIGNED)) AS last_id FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu không có bản ghi nào, bắt đầu từ 1
        $last_id = isset($result['last_id']) ? $result['last_id'] : 0;

        // Tạo MaDatHang mới có dạng DGNCNT+X
        return 'DGNCNT' . str_pad($last_id + 1, 3, '0', STR_PAD_LEFT);
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
        // Tạo MaDatHang mới
        $this->MaDatHang = $this->generateMaDatHang();

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
