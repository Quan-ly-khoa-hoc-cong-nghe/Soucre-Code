<?php
class ThongBao {
    private $conn;
    private $table = "ThongBao";

    public $MaThongBao;
    public $TieuDe;
    public $FileThongBao;
    public $MaNguoiDung;
    public $NgayThongBao;

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

    // Hàm xử lý phản hồi lỗi
    private function sendErrorResponse($message) {
        echo json_encode(["message" => $message]);
        http_response_code(400);
    }

    // Thêm thông báo mới
    public function add() {
        // Kiểm tra xem mã người dùng có tồn tại không
        if (!$this->isNguoiDungExist()) {
            $this->sendErrorResponse("Mã người dùng không tồn tại");
            return false;
        }

        // Kiểm tra nếu thông báo đã tồn tại
        if ($this->isThongBaoExist()) {
            $this->sendErrorResponse("Thông báo đã tồn tại");
            return false;
        }

        // Lưu thời gian thông báo
        $this->NgayThongBao = date('Y-m-d H:i:s');

        $query = "INSERT INTO " . $this->table . " (MaThongBao, TieuDe, FileThongBao, MaNguoiDung, NgayThongBao) 
                  VALUES (:MaThongBao, :TieuDe, :FileThongBao, :MaNguoiDung, :NgayThongBao)";
        $stmt = $this->conn->prepare($query);

        // Ràng buộc tham số
        $stmt->bindParam(":MaThongBao", $this->MaThongBao);
        $stmt->bindParam(":TieuDe", $this->TieuDe);
        $stmt->bindParam(":FileThongBao", $this->FileThongBao);
        $stmt->bindParam(":MaNguoiDung", $this->MaNguoiDung);
        $stmt->bindParam(":NgayThongBao", $this->NgayThongBao);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông báo
    public function update() {
        // Kiểm tra xem thông báo có tồn tại không
        if (!$this->isThongBaoExist()) {
            $this->sendErrorResponse("Thông báo không tồn tại");
            return false;
        }

        // Kiểm tra mã người dùng có tồn tại không
        if (!$this->isNguoiDungExist()) {
            $this->sendErrorResponse("Mã người dùng không tồn tại");
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
            $this->sendErrorResponse("Thông báo không tồn tại");
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
