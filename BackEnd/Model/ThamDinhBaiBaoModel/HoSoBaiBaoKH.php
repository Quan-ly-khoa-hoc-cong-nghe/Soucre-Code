<?php

class HoSoBaiBaoKH
{
    private $conn;
    private $table_name = "HoSoBaiBaoKH";

    // Các thuộc tính
    public $MaHoSo;
    public $TrangThai;
    public $MaNguoiDung;
    public $NgayNop;
    public $MaTacGia;
    public $MaKhoa;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức tạo mới hồ sơ
    public function add()
    {
        $query = "INSERT INTO " . $this->table_name . " (MaHoSo, TrangThai, MaNguoiDung, NgayNop, MaTacGia, MaKhoa)
                  VALUES (:MaHoSo, :TrangThai, :MaNguoiDung, :NgayNop, :MaTacGia, :MaKhoa)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(':MaHoSo', $this->MaHoSo);
        $stmt->bindParam(':TrangThai', $this->TrangThai);
        $stmt->bindParam(':MaNguoiDung', $this->MaNguoiDung);
        $stmt->bindParam(':NgayNop', $this->NgayNop);
        $stmt->bindParam(':MaTacGia', $this->MaTacGia);
        $stmt->bindParam(':MaKhoa', $this->MaKhoa);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Phương thức đọc tất cả hồ sơ
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Phương thức cập nhật hồ sơ
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET TrangThai = :TrangThai, MaNguoiDung = :MaNguoiDung, NgayNop = :NgayNop, MaTacGia = :MaTacGia, MaKhoa = :MaKhoa
                  WHERE MaHoSo = :MaHoSo";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(':MaHoSo', $this->MaHoSo);
        $stmt->bindParam(':TrangThai', $this->TrangThai);
        $stmt->bindParam(':MaNguoiDung', $this->MaNguoiDung);
        $stmt->bindParam(':NgayNop', $this->NgayNop);
        $stmt->bindParam(':MaTacGia', $this->MaTacGia);
        $stmt->bindParam(':MaKhoa', $this->MaKhoa);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Phương thức xóa hồ sơ
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHoSo = :MaHoSo";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc dữ liệu
        $stmt->bindParam(':MaHoSo', $this->MaHoSo);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
