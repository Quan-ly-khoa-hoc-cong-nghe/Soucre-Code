<?php
class GiangVienNCKHSV {
    private $conn;
    private $table_name = "GiangVienNCKHSV";

    public $MaNhomNCKHSV;
    public $MaGV;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaNhomNCKHSV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Lấy bản ghi theo MaNhomNCKHSV
    public function readOne() {
        if (empty($this->MaNhomNCKHSV)) {
            return ["error" => "MaNhomNCKHSV không được để trống"];
        }
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm mới bản ghi
    public function add() {
        if (empty($this->MaNhomNCKHSV) || empty($this->MaGV)) {
            return ["error" => "MaNhomNCKHSV và MaGV không được để trống"];
        }
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, MaGV) 
                    VALUES (:maNhomNCKHSV, :maGV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maGV', $this->MaGV);
            return $stmt->execute() ? ["success" => "Thêm thành công"] : ["error" => "Thêm thất bại"];
        } catch (PDOException $e) {
            return ["error" => "Lỗi thêm mới: " . $e->getMessage()];
        }
    }

    // Cập nhật bản ghi
    public function update() {
        if (empty($this->MaNhomNCKHSV) || empty($this->MaGV)) {
            return ["error" => "MaNhomNCKHSV và MaGV không được để trống"];
        }
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET MaGV = :maGV 
                    WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':maGV', $this->MaGV);
            return $stmt->execute() ? ["success" => "Cập nhật thành công"] : ["error" => "Cập nhật thất bại"];
        } catch (PDOException $e) {
            return ["error" => "Lỗi cập nhật: " . $e->getMessage()];
        }
    }

    // Xóa bản ghi
    public function delete() {
        if (empty($this->MaNhomNCKHSV)) {
            return ["error" => "MaNhomNCKHSV không được để trống"];
        }
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute() ? ["success" => "Xóa thành công"] : ["error" => "Xóa thất bại"];
        } catch (PDOException $e) {
            return ["error" => "Lỗi xóa: " . $e->getMessage()];
        }
    }
}
?>
