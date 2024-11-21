<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DonDatHang.php';

$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Thay YOUR_ACCESS_TOKEN_HERE bằng Access Token hợp lệ
$dondathang = new DonDatHang($db, $accessToken);

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
                $stmt = $dondathang->getAll();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } elseif ($action === "getOne") {
                $maDatHang = isset($_GET['ma_dat_hang']) ? $_GET['ma_dat_hang'] : null;
                if (!$maDatHang) {
                    echo json_encode(["message" => "Thiếu mã đơn đặt hàng"]);
                    http_response_code(400);
                    exit;
                }
                $dondathang->ma_dat_hang = $maDatHang;
                $data = $dondathang->getOne();
                if ($data) {
                    echo json_encode($data);
                } else {
                    echo json_encode(["message" => "Không tìm thấy đơn đặt hàng"]);
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

            if (!isset($_FILES['file']) || !isset($_POST['ma_dat_hang']) || !isset($_POST['ngay_dat']) || !isset($_POST['ma_doi_tac'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];

            $dondathang->ma_dat_hang = $_POST['ma_dat_hang'];
            $dondathang->ngay_dat = $_POST['ngay_dat'];
            $dondathang->ma_doi_tac = $_POST['ma_doi_tac'];

            if ($dondathang->add($filePath, $fileName)) {
                echo json_encode(["message" => "Đơn đặt hàng được thêm thành công"]);
            } else {
                echo json_encode(["message" => "Thêm đơn đặt hàng thất bại"]);
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
            if (!isset($_PUT['ma_dat_hang']) || !isset($_PUT['ngay_dat']) || !isset($_PUT['ma_doi_tac'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
            $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

            $dondathang->ma_dat_hang = $_PUT['ma_dat_hang'];
            $dondathang->ngay_dat = $_PUT['ngay_dat'];
            $dondathang->ma_doi_tac = $_PUT['ma_doi_tac'];

            if ($dondathang->update($filePath, $fileName)) {
                echo json_encode(["message" => "Đơn đặt hàng được cập nhật thành công"]);
            } else {
                echo json_encode(["message" => "Cập nhật đơn đặt hàng thất bại"]);
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
            if (!isset($_DELETE['ma_dat_hang'])) {
                echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
                http_response_code(400);
                exit;
            }

            $dondathang->ma_dat_hang = $_DELETE['ma_dat_hang'];

            if ($dondathang->delete()) {
                echo json_encode(["message" => "Đơn đặt hàng và file trên Google Drive được xóa thành công"]);
            } else {
                echo json_encode(["message" => "Xóa đơn đặt hàng hoặc file thất bại"]);
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
