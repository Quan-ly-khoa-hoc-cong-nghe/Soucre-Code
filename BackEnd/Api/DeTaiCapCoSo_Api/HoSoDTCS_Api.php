<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/HoSoDTCS.php';

// Kết nối database
$database = new Database();
$db = $database->getConn();

// Access Token và Refresh Token từ OAuth Playground
$accessToken = 'YOUR_ACCESS_TOKEN';
$refreshToken = 'YOUR_REFRESH_TOKEN';
$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

// Khởi tạo class HoSoDTCS
$hoso = new HoSoDTCS($db, $accessToken, $refreshToken, $clientId, $clientSecret);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            if ($action === "get") {
                $stmt = $hoso->getAll();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(["data" => $result]);
            } elseif ($action === "getOne") {
                $hoso->ma_ho_so = $_GET['ma_ho_so'] ?? null;
                echo json_encode(["data" => $hoso->getOne()]);
            }
            break;

        case 'POST':
            if ($action === "add") {
                $filePath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];

                $hoso->ma_ho_so = $_POST['ma_ho_so'];
                $hoso->ngay_nop = $_POST['ngay_nop'];
                $hoso->trang_thai = $_POST['trang_thai'];
                $hoso->ma_khoa = $_POST['ma_khoa'];

                echo json_encode([
                    "success" => $hoso->add($filePath, $fileName)
                ]);
            }
            break;

        case 'DELETE':
            if ($action === "delete") {
                $hoso->ma_ho_so = $_GET['ma_ho_so'];
                echo json_encode([
                    "success" => $hoso->delete()
                ]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Phương thức không được hỗ trợ."]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
?>
