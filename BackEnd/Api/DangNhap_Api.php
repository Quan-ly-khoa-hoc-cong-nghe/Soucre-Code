<?php
// Cấu hình CORS
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các nguồn (origins) truy cập
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  // Các phương thức HTTP được phép
header("Access-Control-Allow-Headers: Content-Type");  // Cho phép Content-Type header

// Xử lý yêu cầu OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;  // Nếu là yêu cầu OPTIONS, trả về ngay mà không thực thi mã PHP
}

header("Content-Type: application/json; charset=UTF-8");
require_once '../config/Database.php';

// Kết nối cơ sở dữ liệu
$db = new Database();
$conn = $db->getConn();

// Nếu kết nối không thành công, trả về lỗi
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? ''; // Thực hiện hành động từ tham số 'action' trong URL

// Xử lý các hành động CRUD
switch ($action) {
    case 'get':
        getAllUsers($conn);  // Lấy tất cả người dùng
        break;
    case 'add':
        addUser($conn, $data);  // Thêm người dùng
        break;
    case 'update':
        updateUser($conn, $data);  // Cập nhật người dùng
        break;
    case 'delete':
        deleteUser($conn, $data);  // Xóa người dùng
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// Hàm lấy tất cả người dùng
function getAllUsers($conn) {
    try {
        $sql = "SELECT * FROM NguoiDung where MaNhanVien  = 'NV01' and MatKhau = 'admin123' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $users], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi truy vấn: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm thêm người dùng
function addUser($conn, $data) {
    try {
        $sql = "INSERT INTO NguoiDung (MaNguoiDung, VaiTro, MatKhau, MaNhanVien) VALUES (:MaNguoiDung, :VaiTro, :MatKhau, :MaNhanVien)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->bindValue(':VaiTro', $data['VaiTro']);
        $stmt->bindValue(':MatKhau', $data['MatKhau']);
        $stmt->bindValue(':MaNhanVien', $data['MaNhanVien']);
        $stmt->execute();
        echo json_encode(['message' => 'Thêm người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi thêm người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm cập nhật người dùng
function updateUser($conn, $data) {
    try {
        $sql = "UPDATE NguoiDung SET VaiTro = :VaiTro, MatKhau = :MatKhau, MaNhanVien = :MaNhanVien WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->bindValue(':VaiTro', $data['VaiTro']);
        $stmt->bindValue(':MatKhau', $data['MatKhau']);
        $stmt->bindValue(':MaNhanVien', $data['MaNhanVien']);
        $stmt->execute();
        echo json_encode(['message' => 'Cập nhật người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi cập nhật người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm xóa người dùng
function deleteUser($conn, $data) {
    try {
        $sql = "DELETE FROM NguoiDung WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->execute();
        echo json_encode(['message' => 'Xóa người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi xóa người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
?>