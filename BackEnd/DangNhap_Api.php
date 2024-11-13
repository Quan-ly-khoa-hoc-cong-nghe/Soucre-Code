<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConn();

if (!$conn) {
    echo "Kết nối thất bại!";
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['MaNguoiDung']) || !isset($data['MatKhau'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$maNguoiDung = $data['MaNguoiDung'];
$matKhau = $data['MatKhau'];

$sql = "SELECT * FROM NguoiDung WHERE MaNguoiDung = :maNguoiDung AND MatKhau = :matKhau";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bindValue(':maNguoiDung', $maNguoiDung);
    $stmt->bindValue(':matKhau', $matKhau);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['status' => 'success', 'data' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
} else {
    echo "Lỗi truy vấn SQL.";
}
?>
