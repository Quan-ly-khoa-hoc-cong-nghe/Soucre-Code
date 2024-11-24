<?php
class ThongBao {
    private $conn;
    private $table = "ThongBao";

    public $MaThongBao;
    public $TieuDe;
    public $FileThongBao;
    public $MaNguoiDung;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra xem thông báo đã tồn tại chưa
    private function isThongBaoExist() {
        $query = "SELECT MaThongBao FROM " . $this->table . " WHERE MaThongBao = :MaThongBao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Kiểm tra xem mã người dùng có tồn tại trong bảng NguoiDung không
    private function isNguoiDungExist() {
        $query = "SELECT MaNguoiDung FROM NguoiDung WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    // Thêm thông báo mới
    public function add() {
        // Kiểm tra xem mã người dùng có tồn tại không
        if (!$this->isNguoiDungExist()) {
            echo json_encode(["message" => "Mã người dùng không tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra nếu thông báo đã tồn tại
        if ($this->isThongBaoExist()) {
            echo json_encode(["message" => "Thông báo đã tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (MaThongBao, TieuDe, FileThongBao, MaNguoiDung) 
                  VALUES (:MaThongBao, :TieuDe, :FileThongBao, :MaNguoiDung)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);
        $stmt->bindParam(":TieuDe", $this->TieuDe);
        $stmt->bindParam(":FileThongBao", $this->FileThongBao);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông báo
    public function update() {
        // Kiểm tra xem thông báo có tồn tại không
        if (!$this->isThongBaoExist()) {
            echo json_encode(["message" => "Thông báo không tồn tại"]);
            http_response_code(400);
            return false;
        }

        // Kiểm tra mã người dùng có tồn tại không
        if (!$this->isNguoiDungExist()) {
            echo json_encode(["message" => "Mã người dùng không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                  SET TieuDe = :TieuDe, FileThongBao = :FileThongBao, MaNguoiDung = :MaNguoiDung 
                  WHERE MaThongBao = :MaThongBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);
        $stmt->bindParam(":TieuDe", $this->TieuDe);
        $stmt->bindParam(":FileThongBao", $this->FileThongBao);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa thông báo
    public function delete() {
        // Kiểm tra xem thông báo có tồn tại không
        if (!$this->isThongBaoExist()) {
            echo json_encode(["message" => "Thông báo không tồn tại"]);
            http_response_code(400);
            return false;
        }

        $query = "DELETE FROM " . $this->table . " WHERE MaThongBao = :MaThongBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy tất cả thông báo
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy thông báo theo ID
    public function getById() {
        $query = "SELECT * FROM " . $this->table . " WHERE MaThongBao = :MaThongBao";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);
        $stmt->execute();
        return $stmt;
    }
}
?>
