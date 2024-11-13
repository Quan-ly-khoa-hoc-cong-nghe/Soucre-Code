<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConn();

if (!$conn) {
    echo "Kết nối thất bại!";
    exit;
}


try {
    $sql = "SELECT MaNguoiDung, VaiTro, MaNhanVien FROM NguoiDung";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([ 'data' => $users], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([ 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
}
?>
