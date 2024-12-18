<?php
class GiangVienNCKHSV
{
    private $conn;
    private $table_name = "GiangVienNCKHSV"; // Sửa tên bảng cho đúng

    public $MaNhomNCKHSV;
    public $MaGV;
    public $VaiTro;  // Thêm VaiTro vào model

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả dữ liệu
    public function readAll()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY MaGV ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }

    // Thêm dữ liệu mới
    public function add()
    {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (MaNhomNCKHSV, VaiTro, MaGV) 
                VALUES (:maNhomNCKHSV, :vaiTro, :maGV)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            $stmt->bindParam(':vaiTro', $this->VaiTro);
            $stmt->bindParam(':maGV', $this->MaGV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Cập nhật dữ liệu
    public function update()
    {
        try {
            $sql = "UPDATE " . $this->table_name . " 
                SET VaiTro = :vaiTro, MaGV = :maGV 
                WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':vaiTro', $this->VaiTro);
            $stmt->bindParam(':maGV', $this->MaGV);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    // Xóa dữ liệu
    public function delete()
    {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE MaNhomNCKHSV = :maNhomNCKHSV";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maNhomNCKHSV', $this->MaNhomNCKHSV);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["error" => "Lỗi: " . $e->getMessage()];
        }
    }

    public function readByDeTai($maDeTaiSV)
    {
        try {
            $sql = "SELECT gv.MaGV, gv.HoTenGV, gv.EmailGV, gvnck.VaiTro
                    FROM GiangVienNCKHSV gvnck
                    JOIN NhomNCKHSV nh ON gvnck.MaNhomNCKHSV = nh.MaNhomNCKHSV
                    JOIN DeTaiNCKHSV dt ON nh.MaDeTaiSV = dt.MaDeTaiSV
                    JOIN GiangVien gv ON gvnck.MaGV = gv.MaGV
                    WHERE dt.MaDeTaiSV = :maDeTaiSV";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDeTaiSV', $maDeTaiSV);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Lỗi truy vấn: " . $e->getMessage()];
        }
    }
}
