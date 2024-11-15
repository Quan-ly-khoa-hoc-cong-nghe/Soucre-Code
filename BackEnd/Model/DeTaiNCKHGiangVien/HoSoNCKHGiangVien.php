<?php
require_once '/../../config/Database.php';

class KhoaHoSo {
    private $conn;
    private $tableKhoa = "Khoa";
    private $tableHoSo = "HoSoNCKHGV";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConn();
    }

    // Đọc thông tin từ cả hai bảng
    public function getAll() {
        $query = "SELECT k.MaKhoa, k.TenKhoa, k.VanPhongKHoa, h.MaHoSo, h.NgayNop, h.FileHoSo, h.TrangThai
                  FROM " . $this->tableKhoa . " k
                   JOIN " . $this->tableHoSo . " h ON k.MaKhoa = h.MaKhoa";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm mới vào cả hai bảng
    public function create($data) {
        try {
            $this->conn->beginTransaction();

            // Thêm vào bảng Khoa
            $queryKhoa = "INSERT INTO " . $this->tableKhoa . " SET MaKhoa=:MaKhoa, TenKhoa=:TenKhoa, VanPhongKHoa=:VanPhongKHoa";
            $stmtKhoa = $this->conn->prepare($queryKhoa);
            $stmtKhoa->execute([
                'MaKhoa' => $data['MaKhoa'],
                'TenKhoa' => $data['TenKhoa'],
                'VanPhongKHoa' => $data['VanPhongKHoa']
            ]);

            // Thêm vào bảng HoSoNCKHGV
            $queryHoSo = "INSERT INTO " . $this->tableHoSo . " SET MaHoSo=:MaHoSo, NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai, MaKhoa=:MaKhoa";
            $stmtHoSo = $this->conn->prepare($queryHoSo);
            $stmtHoSo->execute([
                'MaHoSo' => $data['MaHoSo'],
                'NgayNop' => $data['NgayNop'],
                'FileHoSo' => $data['FileHoSo'],
                'TrangThai' => $data['TrangThai'],
                'MaKhoa' => $data['MaKhoa']
            ]);

            $this->conn->commit();
            return ["message" => "Thêm mới thành công"];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["error" => $e->getMessage()];
        }
    }

    // Cập nhật cả hai bảng
    public function update($data) {
        try {
            $this->conn->beginTransaction();

            // Cập nhật bảng Khoa
            $queryKhoa = "UPDATE " . $this->tableKhoa . " SET TenKhoa=:TenKhoa, VanPhongKHoa=:VanPhongKHoa WHERE MaKhoa=:MaKhoa";
            $stmtKhoa = $this->conn->prepare($queryKhoa);
            $stmtKhoa->execute([
                'MaKhoa' => $data['MaKhoa'],
                'TenKhoa' => $data['TenKhoa'],
                'VanPhongKHoa' => $data['VanPhongKHoa']
            ]);

            // Cập nhật bảng HoSoNCKHGV
            $queryHoSo = "UPDATE " . $this->tableHoSo . " SET NgayNop=:NgayNop, FileHoSo=:FileHoSo, TrangThai=:TrangThai WHERE MaHoSo=:MaHoSo";
            $stmtHoSo = $this->conn->prepare($queryHoSo);
            $stmtHoSo->execute([
                'MaHoSo' => $data['MaHoSo'],
                'NgayNop' => $data['NgayNop'],
                'FileHoSo' => $data['FileHoSo'],
                'TrangThai' => $data['TrangThai']
            ]);

            $this->conn->commit();
            return ["message" => "Cập nhật thành công"];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["error" => $e->getMessage()];
        }
    }

    // Xóa thông tin từ cả hai bảng
    public function delete($MaKhoa, $MaHoSo) {
        try {
            $this->conn->beginTransaction();

            // Xóa từ bảng HoSoNCKHGV
            $queryHoSo = "DELETE FROM " . $this->tableHoSo . " WHERE MaHoSo=:MaHoSo";
            $stmtHoSo = $this->conn->prepare($queryHoSo);
            $stmtHoSo->execute(['MaHoSo' => $MaHoSo]);

            // Xóa từ bảng Khoa
            $queryKhoa = "DELETE FROM " . $this->tableKhoa . " WHERE MaKhoa=:MaKhoa";
            $stmtKhoa = $this->conn->prepare($queryKhoa);
            $stmtKhoa->execute(['MaKhoa' => $MaKhoa]);

            $this->conn->commit();
            return ["message" => "Xóa thành công"];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["error" => $e->getMessage()];
        }
    }
}
?>
