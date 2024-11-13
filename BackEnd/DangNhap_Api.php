<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'Database.php';

$db = new Database();
$conn = $db->getConn();

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';


switch ($action) {
    case 'get':
        getAllUsers($conn);
        break;
    case 'add':
        addUser($conn, $data);
        break;
    case 'update':
        updateUser($conn, $data);
        break;
    case 'delete':
        deleteUser($conn, $data);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function getAllUsers($conn)
{
    try {
        $sql = "SELECT * FROM NguoiDung";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([ 'data' => $users], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi truy vấn: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}


function addUser($conn, $data)
{
    try {
        $sql = "INSERT INTO NguoiDung (MaNguoiDung, VaiTro, MatKhau, MaNhanVien) VALUES (:MaNguoiDung, :VaiTro, :MatKhau, :MaNhanVien)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->bindValue(':VaiTro', $data['VaiTro']);
        $stmt->bindValue(':MatKhau', $data['MatKhau']);
        $stmt->bindValue(':MaNhanVien', $data['MaNhanVien']);
        $stmt->execute();
        echo json_encode([ 'message' => 'Thêm người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode([ 'message' => 'Lỗi thêm người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}


function updateUser($conn, $data)
{
    try {
        $sql = "UPDATE NguoiDung SET VaiTro = :VaiTro, MatKhau = :MatKhau, MaNhanVien = :MaNhanVien WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->bindValue(':VaiTro', $data['VaiTro']);
        $stmt->bindValue(':MatKhau', $data['MatKhau']);
        $stmt->bindValue(':MaNhanVien', $data['MaNhanVien']);
        $stmt->execute();
        echo json_encode([ 'message' => 'Cập nhật người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode([ 'message' => 'Lỗi cập nhật người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}


function deleteUser($conn, $data)
{
    try {
        $sql = "DELETE FROM NguoiDung WHERE MaNguoiDung = :MaNguoiDung";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $data['MaNguoiDung']);
        $stmt->execute();
        echo json_encode([ 'message' => 'Xóa người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode([ 'message' => 'Lỗi xóa người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
?>
