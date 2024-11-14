<?php

class TacGiaBaiBaoGiangVien
{
    private $conn;
    private $table_giang_vien = "GiangVien";
    private $table_tac_gia_giang_vien = "TacGiaGiangVien";
    private $table_tac_gia_bai_bao = "TacGiaBaiBao";

    // Các thuộc tính
    public $MaTacGia;
    public $MaGV;
    public $HoTenGV;
    public $NgaySinhGV;
    public $EmailGV;
    public $DiaChiGV;
    public $DiemNCKH;
    public $MaKhoa;
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

            // Thêm giảng viên vào bảng GiangVien
            $query1 = "INSERT INTO " . $this->table_giang_vien . " (MaGV, HoTenGV, NgaySinhGV, EmailGV, DiaChiGV, DiemNCKH, MaKhoa)
                       VALUES (:MaGV, :HoTenGV, :NgaySinhGV, :EmailGV, :DiaChiGV, :DiemNCKH, :MaKhoa)";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':MaGV', $this->MaGV);
            $stmt1->bindParam(':HoTenGV', $this->HoTenGV);
            $stmt1->bindParam(':NgaySinhGV', $this->NgaySinhGV);
            $stmt1->bindParam(':EmailGV', $this->EmailGV);
            $stmt1->bindParam(':DiaChiGV', $this->DiaChiGV);
            $stmt1->bindParam(':DiemNCKH', $this->DiemNCKH);
            $stmt1->bindParam(':MaKhoa', $this->MaKhoa);
            $stmt1->execute();

            // Thêm tác giả vào bảng TacGiaGiangVien
            $query2 = "INSERT INTO " . $this->table_tac_gia_giang_vien . " (MaTacGia, MaGV, VaiTro)
                       VALUES (:MaTacGia, :MaGV, :VaiTro)";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt2->bindParam(':MaGV', $this->MaGV);
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
        $query = "SELECT gv.MaGV, gv.HoTenGV, gv.EmailGV, tggv.VaiTro, tgbb.MaBaiBao
                  FROM " . $this->table_giang_vien . " gv
                  JOIN " . $this->table_tac_gia_giang_vien . " tggv ON gv.MaGV = tggv.MaGV
                  JOIN " . $this->table_tac_gia_bai_bao . " tgbb ON tggv.MaTacGia = tgbb.MaTacGia";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Phương thức xóa bản ghi từ 3 bảng
    public function delete()
    {
        try {
            $this->conn->beginTransaction();

            $query1 = "DELETE FROM " . $this->table_tac_gia_bai_bao . " WHERE MaTacGia = :MaTacGia";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt1->execute();

            $query2 = "DELETE FROM " . $this->table_tac_gia_giang_vien . " WHERE MaTacGia = :MaTacGia";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
            $stmt2->execute();

            $query3 = "DELETE FROM " . $this->table_giang_vien . " WHERE MaGV = :MaGV";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':MaGV', $this->MaGV);
            $stmt3->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Phương thức cập nhật bản ghi trong 3 bảng
public function update()
{
    try {
        $this->conn->beginTransaction();

        // Cập nhật thông tin giảng viên trong bảng GiangVien
        $query1 = "UPDATE " . $this->table_giang_vien . "
                   SET HoTenGV = :HoTenGV, NgaySinhGV = :NgaySinhGV, EmailGV = :EmailGV, 
                       DiaChiGV = :DiaChiGV, DiemNCKH = :DiemNCKH, MaKhoa = :MaKhoa
                   WHERE MaGV = :MaGV";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(':MaGV', $this->MaGV);
        $stmt1->bindParam(':HoTenGV', $this->HoTenGV);
        $stmt1->bindParam(':NgaySinhGV', $this->NgaySinhGV);
        $stmt1->bindParam(':EmailGV', $this->EmailGV);
        $stmt1->bindParam(':DiaChiGV', $this->DiaChiGV);
        $stmt1->bindParam(':DiemNCKH', $this->DiemNCKH);
        $stmt1->bindParam(':MaKhoa', $this->MaKhoa);
        $stmt1->execute();

        // Cập nhật thông tin vai trò trong bảng TacGiaGiangVien
        $query2 = "UPDATE " . $this->table_tac_gia_giang_vien . "
                   SET VaiTro = :VaiTro
                   WHERE MaTacGia = :MaTacGia AND MaGV = :MaGV";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':MaTacGia', $this->MaTacGia);
        $stmt2->bindParam(':MaGV', $this->MaGV);
        $stmt2->bindParam(':VaiTro', $this->VaiTro);
        $stmt2->execute();

        // Cập nhật thông tin bài báo trong bảng TacGiaBaiBao
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

}
?>
