<?php
class DonViDoiTac {
    private $conn;
    private $table = "DonViDoiTac";

    public $MaDoiTac;
    public $TenDoiTac;
    public $SdtDoiTac;
    public $EmailDoiTac;
    public $DiaChiDoiTac;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đối tác
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Lấy thông tin một đối tác
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaDoiTac = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDoiTac);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm đối tác mới
    public function add() {
        $query = "INSERT INTO " . $this->table . " (MaDoiTac, TenDoiTac, SdtDoiTac, EmailDoiTac, DiaChiDoiTac) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->MaDoiTac);
        $stmt->bindParam(2, $this->TenDoiTac);
        $stmt->bindParam(3, $this->SdtDoiTac);
        $stmt->bindParam(4, $this->EmailDoiTac);
        $stmt->bindParam(5, $this->DiaChiDoiTac);

        return $stmt->execute();
    }

    // Cập nhật thông tin đối tác
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET TenDoiTac = ?, SdtDoiTac = ?, EmailDoiTac = ?, DiaChiDoiTac = ? 
                  WHERE MaDoiTac = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->TenDoiTac);
        $stmt->bindParam(2, $this->SdtDoiTac);
        $stmt->bindParam(3, $this->EmailDoiTac);
        $stmt->bindParam(4, $this->DiaChiDoiTac);
        $stmt->bindParam(5, $this->MaDoiTac);

        return $stmt->execute();
    }

    // Xóa đối tác
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE MaDoiTac = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->MaDoiTac);

        return $stmt->execute();
    }
}
?>
