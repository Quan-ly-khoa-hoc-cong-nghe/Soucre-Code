<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/KeHoachNCNT.php';

$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay bằng access token hợp lệ của Google Drive
$kehoach = new KeHoachNCNT($db, $accessToken);

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
                $stmt = $kehoach->getAll();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } elseif ($action === "getOne") {
                $maDuAn = isset($_GET['ma_du_an']) ? $_GET['ma_du_an'] : null;
                if (!$maDuAn) {
                    echo json_encode(["message" => "Thiếu mã dự án"]);
                    http_response_code(400);
                    exit;
                }
                $kehoach->ma_du_an = $maDuAn;
                $data = $kehoach->getOne();
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Action không hợp lệ"]);
                http_response_code(400);
            }
            break;

        case 'POST':
            if ($action !== "post") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
                http_response_code(400);
                exit;
            }

            if (!isset($_FILES['file'], $_POST['ngay_bat_dau'], $_POST['ngay_ket_thuc'], $_POST['kinh_phi'], $_POST['ma_du_an'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $kehoach->ngay_bat_dau = $_POST['ngay_bat_dau'];
            $kehoach->ngay_ket_thuc = $_POST['ngay_ket_thuc'];
            $kehoach->kinh_phi = $_POST['kinh_phi'];
            $kehoach->ma_du_an = $_POST['ma_du_an'];

            if ($kehoach->add($filePath, $fileName)) {
                echo json_encode(["message" => "Kế hoạch được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm kế hoạch thất bại"]);
                http_response_code(500);
            }
            break;

        case 'PUT':
            if ($action !== "put") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
                http_response_code(400);
                exit;
            }

            parse_str(file_get_contents("php://input"), $_PUT);
            if (!isset($_PUT['ma_du_an'], $_PUT['ngay_bat_dau'], $_PUT['ngay_ket_thuc'], $_PUT['kinh_phi'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->ma_du_an = $_PUT['ma_du_an'];
            $kehoach->ngay_bat_dau = $_PUT['ngay_bat_dau'];
            $kehoach->ngay_ket_thuc = $_PUT['ngay_ket_thuc'];
            $kehoach->kinh_phi = $_PUT['kinh_phi'];

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            if ($kehoach->update($filePath, $fileName)) {
                echo json_encode(["message" => "Kế hoạch được cập nhật thành công"]);
            } else {
                echo json_encode(["message" => "Cập nhật kế hoạch thất bại"]);
                http_response_code(500);
            }
            break;

        case 'DELETE':
            if ($action !== "delete") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
                http_response_code(400);
                exit;
            }

            $data = json_decode(file_get_contents("php://input"));
            if (!isset($data->ma_du_an)) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $kehoach->ma_du_an = $data->ma_du_an;

            if ($kehoach->delete()) {
                echo json_encode(["message" => "Kế hoạch được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa kế hoạch thất bại"]);
                http_response_code(500);
            }
            break;

        default:
            echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
            http_response_code(405);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Lỗi: " . $e->getMessage()]);
    http_response_code(500);
}
?>
