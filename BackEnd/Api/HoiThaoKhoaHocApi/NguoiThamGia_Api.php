<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/NguoiThamGia.php';

use Google\Client;
use Google\Service\Drive;

// Khởi tạo Google Client
$accessToken = 'YOUR_ACCESS_TOKEN'; // Thay bằng Access Token của bạn
$client = new Client();
$client->setAccessToken($accessToken);
$database = new Database();
$db = $database->getConn();
$nguoithamgia = new NguoiThamGia($db, $client);

// Lấy phương thức HTTP và tham số `action`
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
                $result = $nguoithamgia->getAll();
                echo json_encode(["data" => $result, "message" => "Lấy tất cả người tham gia thành công"]);
            } elseif ($action === "getOne") {
                $MaNguoiThamGia = $_GET['MaNguoiThamGia'] ?? null;
                if (!$MaNguoiThamGia) {
                    echo json_encode(["message" => "Thiếu mã người tham gia"]);
                    http_response_code(400);
                    exit;
                }
                $nguoithamgia->MaNguoiThamGia = $MaNguoiThamGia;
                $data = $nguoithamgia->getOne();
                if ($data) {
                    echo json_encode(["data" => $data, "message" => "Lấy thông tin người tham gia thành công"]);
                } else {
                    echo json_encode(["message" => "Không tìm thấy người tham gia"]);
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

            if (!isset($_FILES['FileHoSo']) || !isset($_POST['MaNguoiThamGia']) || !isset($_POST['TenNguoiThamGia']) ||
                !isset($_POST['Sdt']) || !isset($_POST['Email']) || !isset($_POST['HocHam']) || !isset($_POST['HocVi'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['FileHoSo']['tmp_name'];
            $fileName = $_FILES['FileHoSo']['name'];

            $nguoithamgia->MaNguoiThamGia = $_POST['MaNguoiThamGia'];
            $nguoithamgia->TenNguoiThamGia = $_POST['TenNguoiThamGia'];
            $nguoithamgia->Sdt = $_POST['Sdt'];
            $nguoithamgia->Email = $_POST['Email'];
            $nguoithamgia->HocHam = $_POST['HocHam'];
            $nguoithamgia->HocVi = $_POST['HocVi'];

            if ($nguoithamgia->add($filePath, $fileName)) {
                echo json_encode(["message" => "Người tham gia được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm người tham gia thất bại"]);
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
            if (!isset($_PUT['MaNguoiThamGia']) || !isset($_PUT['TenNguoiThamGia']) ||
                !isset($_PUT['Sdt']) || !isset($_PUT['Email']) || !isset($_PUT['HocHam']) ||
                !isset($_PUT['HocVi'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = isset($_FILES['FileHoSo']['tmp_name']) ? $_FILES['FileHoSo']['tmp_name'] : null;
            $fileName = isset($_FILES['FileHoSo']['name']) ? $_FILES['FileHoSo']['name'] : null;

            $nguoithamgia->MaNguoiThamGia = $_PUT['MaNguoiThamGia'];
            $nguoithamgia->TenNguoiThamGia = $_PUT['TenNguoiThamGia'];
            $nguoithamgia->Sdt = $_PUT['Sdt'];
            $nguoithamgia->Email = $_PUT['Email'];
            $nguoithamgia->HocHam = $_PUT['HocHam'];
            $nguoithamgia->HocVi = $_PUT['HocVi'];

            if ($nguoithamgia->update($filePath, $fileName)) {
                echo json_encode(["message" => "Người tham gia được cập nhật thành công"]);
            } else {
                echo json_encode(["message" => "Cập nhật người tham gia thất bại"]);
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
            if (!isset($_DELETE['MaNguoiThamGia'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $nguoithamgia->MaNguoiThamGia = $_DELETE['MaNguoiThamGia'];

            if ($nguoithamgia->delete()) {
                echo json_encode(["message" => "Người tham gia được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa người tham gia thất bại"]);
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
