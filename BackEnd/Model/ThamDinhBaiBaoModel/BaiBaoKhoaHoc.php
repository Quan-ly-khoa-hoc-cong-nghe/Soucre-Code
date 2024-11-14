<?php

class BaiBaoKhoaHoc
{
    private $conn;
    private $table_name = "BaiBaoKhoaHoc";

    // Các thuộc tính
    public $maBaiBaoKhoaHoc;
    public $tenBaiBaoKhoaHoc;
    public $urlBaiBaoKhoaHoc;
    public $NgayXuatBan;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức lấy tất cả bài báo
    public function readAll()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY NgayXuatBan DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Phương thức thêm bài báo
    public function add()
    {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (tenBaiBaoKhoaHoc, urlBaiBaoKhoaHoc, NgayXuatBan) 
                    VALUES (:ten, :url, :ngay)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ten', $this->tenBaiBaoKhoaHoc);
            $stmt->bindParam(':url', $this->urlBaiBaoKhoaHoc);
            $stmt->bindParam(':ngay', $this->NgayXuatBan);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Phương thức cập nhật bài báo
    public function update()
    {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                    SET tenBaiBaoKhoaHoc = :ten, urlBaiBaoKhoaHoc = :url, NgayXuatBan = :ngay 
                    WHERE maBaiBaoKhoaHoc = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->maBaiBaoKhoaHoc);
            $stmt->bindParam(':ten', $this->tenBaiBaoKhoaHoc);
            $stmt->bindParam(':url', $this->urlBaiBaoKhoaHoc);
            $stmt->bindParam(':ngay', $this->NgayXuatBan);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Phương thức xóa bài báo
    public function delete()
    {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE maBaiBaoKhoaHoc = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->maBaiBaoKhoaHoc);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }
}
?>
