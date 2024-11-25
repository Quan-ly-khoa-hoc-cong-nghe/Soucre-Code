<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHSinhVien/DeTaiNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

// Thay YOUR_ACCESS_TOKEN bằng Access Token hợp lệ
$accessToken = 'YOUR_ACCESS_TOKEN';
$deTai = new DeTaiNCKHSV($conn, $accessToken);

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
            if ($action === "getAll") {
                $result = $deTai->getAllDeTai();
                echo json_encode(['data' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } elseif ($action === "getOne") {
                $maDeTai = isset($_GET['ma_de_tai']) ? $_GET['ma_de_tai'] : null;
                if (!$maDeTai) {
                    echo json_encode(["message" => "Thiếu mã đề tài"]);
                    http_response_code(400);
                    exit;
                }
                $deTaiData = $deTai->getDeTaiByMa($maDeTai);
                if ($deTaiData) {
                    echo json_encode(['data' => $deTaiData], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(["message" => "Không tìm thấy đề tài"]);
                    http_response_code(404);
                }
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

            if (!isset($_POST['ma_de_tai']) || !isset($_POST['ten_de_tai']) || !isset($_POST['mo_ta']) || !isset($_POST['trang_thai']) || !isset($_POST['ma_ho_so']) || !isset($_POST['ma_nhom']) || !isset($_FILES['file_hop_dong'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $deTai->ma_de_tai = $_POST['ma_de_tai'];
            $deTai->ten_de_tai = $_POST['ten_de_tai'];
            $deTai->mo_ta = $_POST['mo_ta'];
            $deTai->trang_thai = $_POST['trang_thai'];
            $deTai->ma_ho_so = $_POST['ma_ho_so'];
            $deTai->ma_nhom = $_POST['ma_nhom'];
            $filePath = $_FILES['file_hop_dong']['tmp_name'];
            $fileName = $_FILES['file_hop_dong']['name'];

            if ($deTai->addDeTai()) {
                echo json_encode(["message" => "Đề tài được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm đề tài thất bại"]);
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

            if (!isset($_PUT['ma_de_tai']) || !isset($_PUT['ten_de_tai']) || !isset($_PUT['mo_ta']) || !isset($_PUT['trang_thai']) || !isset($_PUT['ma_ho_so']) || !isset($_PUT['ma_nhom'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $deTai->ma_de_tai = $_PUT['ma_de_tai'];
            $deTai->ten_de_tai = $_PUT['ten_de_tai'];
            $deTai->mo_ta = $_PUT['mo_ta'];
            $deTai->trang_thai = $_PUT['trang_thai'];
            $deTai->ma_ho_so = $_PUT['ma_ho_so'];
            $deTai->ma_nhom = $_PUT['ma_nhom'];

            if (isset($_FILES['file_hop_dong']) && $_FILES['file_hop_dong']['tmp_name']) {
                $filePath = $_FILES['file_hop_dong']['tmp_name'];
                $fileName = $_FILES['file_hop_dong']['name'];
            }

            if ($deTai->updateDeTai()) {
                echo json_encode(["message" => "Đề tài được cập nhật thành công"]);
            } else {
                echo json_encode(["message" => "Cập nhật đề tài thất bại"]);
                http_response_code(500);
            }
            break;

        case 'DELETE':
            if ($action !== "delete") {
                echo json_encode(["message" => "Action không hợp lệ cho phương thức DELETE"]);
                http_response_code(400);
                exit;
            }

            parse_str(file_get_contents("php://input"), $_DELETE);

            if (!isset($_DELETE['ma_de_tai'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $deTai->ma_de_tai = $_DELETE['ma_de_tai'];

            if ($deTai->deleteDeTai()) {
                echo json_encode(["message" => "Đề tài được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa đề tài thất bại"]);
                http_response_code(500);
            }
            break;

        default:
            echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
            http_response_code(405);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Có lỗi xảy ra: " . $e->getMessage()]);
    http_response_code(500);
}
?>
