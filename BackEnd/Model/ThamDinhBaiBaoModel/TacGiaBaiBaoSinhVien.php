<?php

class TacGiaBaiBaoSinhVien
{
    private $conn;
    private $table_sinh_vien = "SinhVien";
    private $table_tac_gia_sinh_vien = "TacGiaSinhVien";
    private $table_tac_gia_bai_bao = "TacGiaBaiBao";

    // Các thuộc tính
    public $MaTacGia;
    public $MaSinhVien;
    public $TenSinhVien;
    public $EmailSV;
    public $sdtSV;
    public $VaiTro;
    public $MaBaiBao;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức tạo mới bản ghi cho cả 3 bảng
    public function add()
    {
        try {
            $this->conn->beginTransaction();

            // Thêm sinh viên vào bảng SinhVien
            $query1 = "INSERT INTO " . $this->table_sinh_vien . " (MaSinhVien, TenSinhVien, EmailSV, sdtSV)
                       VALUES (:MaSinhVien, :TenSinhVien, :EmailSV, :sdtSV)";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':MaSinhVien', $this->MaSinhVien);
            $stmt1->bindParam(':TenSinhVien', $this->TenSinhVien);
            $stmt1->bindParam(':EmailSV', $this->EmailSV);
            $stmt1->bindParam(':sdtSV', $this->sdtSV);
            $stmt1->execute();

            // Thêm tác giả vào bảng TacGiaSinhVien
            $query2 = "INSERT INTO " . $this->table_tac_gia_sinh_vien . " (MaTacGia, MaSinhVien, VaiTro)
                       VALUES (:MaTacGia, :MaSinhVien, :VaiTro)";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt2->bindParam(':MaSinhVien', $this->MaSinhVien);
            $stmt2->bindParam(':VaiTro', $this->VaiTro);
            $stmt2->execute();

            // Thêm bài báo vào bảng TacGiaBaiBao
            $query3 = "INSERT INTO " . $this->table_tac_gia_bai_bao . " (MaTacGia, MaBaiBao)
                       VALUES (:MaTacGia, :MaBaiBao)";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt3->bindParam(':MaBaiBao', $this->MaBaiBao);
            $stmt3->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Phương thức đọc dữ liệu từ 3 bảng
    public function read()
    {
        $query = "SELECT sv.MaSinhVien, sv.TenSinhVien, sv.EmailSV, sv.sdtSV, tgsv.VaiTro, tgbb.MaBaiBao
                  FROM " . $this->table_sinh_vien . " sv
                  JOIN " . $this->table_tac_gia_sinh_vien . " tgsv ON sv.MaSinhVien = tgsv.MaSinhVien
                  JOIN " . $this->table_tac_gia_bai_bao . " tgbb ON tgsv.MaTacGia = tgbb.MaTacGia";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Phương thức cập nhật bản ghi
    public function update()
    {
        try {
            $this->conn->beginTransaction();

            // Cập nhật thông tin sinh viên
            $query1 = "UPDATE " . $this->table_sinh_vien . "
                       SET TenSinhVien = :TenSinhVien, EmailSV = :EmailSV, sdtSV = :sdtSV
                       WHERE MaSinhVien = :MaSinhVien";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':MaSinhVien', $this->MaSinhVien);
            $stmt1->bindParam(':TenSinhVien', $this->TenSinhVien);
            $stmt1->bindParam(':EmailSV', $this->EmailSV);
            $stmt1->bindParam(':sdtSV', $this->sdtSV);
            $stmt1->execute();

            // Cập nhật thông tin vai trò
            $query2 = "UPDATE " . $this->table_tac_gia_sinh_vien . "
                       SET VaiTro = :VaiTro
                       WHERE MaTacGia = :MaTacGia AND MaSinhVien = :MaSinhVien";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt2->bindParam(':MaSinhVien', $this->MaSinhVien);
            $stmt2->bindParam(':VaiTro', $this->VaiTro);
            $stmt2->execute();

            // Cập nhật thông tin bài báo
            $query3 = "UPDATE " . $this->table_tac_gia_bai_bao . "
                       SET MaBaiBao = :MaBaiBao
                       WHERE MaTacGia = :MaTacGia";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt3->bindParam(':MaBaiBao', $this->MaBaiBao);
            $stmt3->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Phương thức xóa bản ghi
    public function delete()
    {
        try {
            $this->conn->beginTransaction();

            $query1 = "DELETE FROM " . $this->table_tac_gia_bai_bao . " WHERE MaTacGia = :MaTacGia";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt1->execute();

            $query2 = "DELETE FROM " . $this->table_tac_gia_sinh_vien . " WHERE MaTacGia = :MaTacGia";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt2->execute();

            $query3 = "DELETE FROM " . $this->table_sinh_vien . " WHERE MaSinhVien = :MaSinhVien";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':MaSinhVien', $this->MaSinhVien);
            $stmt3->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
