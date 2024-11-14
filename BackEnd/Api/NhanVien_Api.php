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

        $sql = "SELECT 
    nv.MaNhanVien, 
    nv.TenNhanVien, 
    nv.sdtNV, 
    nv.EmailNV, 
    nv.PhongCongTac, 
    nd.VaiTro, 
    nd.MatKhau 
FROM NhanVien nv JOIN NguoiDung nd ON nd.MaNhanVien =  nv.MaNhanVien;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([ 'data' => $users], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Lỗi truy vấn: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}


function addUser($conn, $user, $employee)
{
    try {
        $conn->beginTransaction();

        $sqlEmp = "INSERT INTO NhanVien (MaNhanVien, TenNhanVien, sdtNV, EmailNV, PhongCongTac) 
                   VALUES (:MaNhanVien, :TenNhanVien, :sdtNV, :EmailNV, :PhongCongTac)";
        $stmtEmp = $conn->prepare($sqlEmp);
        $stmtEmp->bindValue(':MaNhanVien', $employee['MaNhanVien']);
        $stmtEmp->bindValue(':TenNhanVien', $employee['TenNhanVien']);
        $stmtEmp->bindValue(':sdtNV', $employee['sdtNV']);
        $stmtEmp->bindValue(':EmailNV', $employee['EmailNV']);
        $stmtEmp->bindValue(':PhongCongTac', $employee['PhongCongTac']);

        $stmtEmp->execute();

        $sql = "INSERT INTO NguoiDung (MaNguoiDung, VaiTro, MatKhau, MaNhanVien) 
                VALUES (:MaNguoiDung, :VaiTro, :MatKhau, :MaNhanVien)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MaNguoiDung', $user['MaNguoiDung']);
        $stmt->bindValue(':VaiTro', $user['VaiTro']);
        $stmt->bindValue(':MatKhau', $user['MatKhau']);
        $stmt->bindValue(':MaNhanVien', $user['MaNhanVien']);

        $stmt->execute();

        $conn->commit();

        echo json_encode(['message' => 'Thêm người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['message' => 'Lỗi thêm người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}



function updateUser($conn, $user, $employee)
{
    try {
  
        $conn->beginTransaction();

        $sqlEmp = "UPDATE NhanVien 
                   SET TenNhanVien = :TenNhanVien, sdtNV = :sdtNV, EmailNV = :EmailNV, PhongCongTac = :PhongCongTac 
                   WHERE MaNhanVien = :MaNhanVien";
        $stmtEmp = $conn->prepare($sqlEmp);
        $stmtEmp->bindValue(':MaNhanVien', $employee['MaNhanVien']);
        $stmtEmp->bindValue(':TenNhanVien', $employee['TenNhanVien']);
        $stmtEmp->bindValue(':sdtNV', $employee['sdtNV']);
        $stmtEmp->bindValue(':EmailNV', $employee['EmailNV']);
        $stmtEmp->bindValue(':PhongCongTac', $employee['PhongCongTac']);
        $stmtEmp->execute();

        $sqlUser = "UPDATE NguoiDung 
                    SET VaiTro = :VaiTro, MatKhau = :MatKhau 
                    WHERE MaNguoiDung = :MaNguoiDung";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bindValue(':MaNguoiDung', $user['MaNguoiDung']);
        $stmtUser->bindValue(':VaiTro', $user['VaiTro']);
        $stmtUser->bindValue(':MatKhau', $user['MatKhau']);
        $stmtUser->execute();

        $conn->commit();

        echo json_encode(['message' => 'Cập nhật người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['message' => 'Lỗi cập nhật người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}



function deleteUser($conn, $maNguoiDung, $maNhanVien)
{
    try {
        $conn->beginTransaction();

        $sqlUser = "DELETE FROM NguoiDung WHERE MaNguoiDung = :MaNguoiDung";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bindValue(':MaNguoiDung', $maNguoiDung);
        $stmtUser->execute();

        $sqlEmp = "DELETE FROM NhanVien WHERE MaNhanVien = :MaNhanVien";
        $stmtEmp = $conn->prepare($sqlEmp);
        $stmtEmp->bindValue(':MaNhanVien', $maNhanVien);
        $stmtEmp->execute();

        $conn->commit();

        echo json_encode(['message' => 'Xóa người dùng thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {

        $conn->rollBack();
        echo json_encode(['message' => 'Lỗi xóa người dùng: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

?>
