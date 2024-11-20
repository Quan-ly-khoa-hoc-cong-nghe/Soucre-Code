<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DuAnNCNT.php';

// Khởi tạo kết nối cơ sở dữ liệu
$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay bằng Access Token hợp lệ
$duan = new DuAnNCNT($db, $accessToken);

// Lấy phương thức HTTP và tham số `action`
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Kiểm tra tham số `action`
if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            if ($action === "get") {
                $stmt = $duan->getAll();
                $result = $stmt;
                echo json_encode(["data" => $result, "message" => "Lấy tất cả dự án thành công"]);
            } elseif ($action === "getOne") {
                $maDuAn = isset($_GET['ma_du_an']) ? $_GET['ma_du_an'] : null;
                if (!$maDuAn) {
                    echo json_encode(["message" => "Thiếu mã dự án"]);
                    http_response_code(400);
                    exit;
                }
                $duan->ma_du_an = $maDuAn;
                $data = $duan->getOne();
                if ($data) {
                    echo json_encode(["data" => $data, "message" => "Lấy thông tin dự án thành công"]);
                } else {
                    echo json_encode(["message" => "Không tìm thấy dự án"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['ma_du_an'], $_POST['ten_du_an'], $_POST['ngay_bat_dau'], $_POST['ngay_ket_thuc'], $_POST['trang_thai'], $_POST['ma_ho_so'], $_POST['ma_dat_hang'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $duan->ma_du_an = $_POST['ma_du_an'];
            $duan->ten_du_an = $_POST['ten_du_an'];
            $duan->ngay_bat_dau = $_POST['ngay_bat_dau'];
            $duan->ngay_ket_thuc = $_POST['ngay_ket_thuc'];
            $duan->trang_thai = $_POST['trang_thai'];
            $duan->ma_ho_so = $_POST['ma_ho_so'];
            $duan->ma_dat_hang = $_POST['ma_dat_hang'];

            if ($duan->add($filePath, $fileName)) {
                echo json_encode(["message" => "Dự án được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm dự án thất bại"]);
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
            if (!isset($_PUT['ma_du_an'], $_PUT['ten_du_an'], $_PUT['ngay_bat_dau'], $_PUT['ngay_ket_thuc'], $_PUT['trang_thai'], $_PUT['ma_ho_so'], $_PUT['ma_dat_hang'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $duan->ma_du_an = $_PUT['ma_du_an'];
            $duan->ten_du_an = $_PUT['ten_du_an'];
            $duan->ngay_bat_dau = $_PUT['ngay_bat_dau'];
            $duan->ngay_ket_thuc = $_PUT['ngay_ket_thuc'];
            $duan->trang_thai = $_PUT['trang_thai'];
            $duan->ma_ho_so = $_PUT['ma_ho_so'];
            $duan->ma_dat_hang = $_PUT['ma_dat_hang'];

            if (!empty($_FILES['file'])) {
                $filePath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                if (!$duan->update($filePath, $fileName)) {
                    echo json_encode(["message" => "Cập nhật dự án thất bại"]);
                    http_response_code(500);
                    exit;
                }
            }

            echo json_encode(["message" => "Dự án được cập nhật thành công"]);
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

            $duan->ma_du_an = $data->ma_du_an;

            if ($duan->delete()) {
                echo json_encode(["message" => "Dự án được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa dự án thất bại"]);
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
