<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/DeTaiCapSo.php';

$database = new Database();
$db = $database->getConn();
$accessToken = 'YOUR_ACCESS_TOKEN_HERE';
$deTai = new DeTaiCapSo($db, $accessToken);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === "getAll") {
            $stmt = $deTai->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $ma_dtcs = isset($_GET['ma_dtcs']) ? $_GET['ma_dtcs'] : null;
            if (!$ma_dtcs) {
                echo json_encode(["message" => "Thiếu mã đề tài"]);
                http_response_code(400);
                exit;
            }
            $deTai->ma_dtcs = $ma_dtcs;
            $data = $deTai->getOne();
            echo json_encode($data);
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'POST':
        if ($action !== "add") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
            exit;
        }

        if (!isset($_FILES['file']) || !isset($_POST['ma_dtcs']) || !isset($_POST['ten_de_tai']) || !isset($_POST['ngay_bat_dau']) || !isset($_POST['ngay_ket_thuc']) || !isset($_POST['trang_thai']) || !isset($_POST['ma_ho_so'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $filePath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];

        $deTai->ma_dtcs = $_POST['ma_dtcs'];
        $deTai->ten_de_tai = $_POST['ten_de_tai'];
        $deTai->ngay_bat_dau = $_POST['ngay_bat_dau'];
        $deTai->ngay_ket_thuc = $_POST['ngay_ket_thuc'];
        $deTai->trang_thai = $_POST['trang_thai'];
        $deTai->ma_ho_so = $_POST['ma_ho_so'];

        if ($deTai->add($filePath, $fileName)) {
            echo json_encode(["message" => "Đề tài được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đề tài thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        parse_str(file_get_contents("php://input"), $_PUT);
        if (!isset($_PUT['ma_dtcs']) || !isset($_PUT['ten_de_tai']) || !isset($_PUT['ngay_bat_dau']) || !isset($_PUT['ngay_ket_thuc']) || !isset($_PUT['trang_thai']) || !isset($_PUT['ma_ho_so'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $filePath = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : null;
        $fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;

        $deTai->ma_dtcs = $_PUT['ma_dtcs'];
        $deTai->ten_de_tai = $_PUT['ten_de_tai'];
        $deTai->ngay_bat_dau = $_PUT['ngay_bat_dau'];
        $deTai->ngay_ket_thuc = $_PUT['ngay_ket_thuc'];
        $deTai->trang_thai = $_PUT['trang_thai'];
        $deTai->ma_ho_so = $_PUT['ma_ho_so'];

        if ($deTai->update($filePath, $fileName)) {
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
        if (!isset($_DELETE['ma_dtcs'])) {
            echo json_encode(["message" => "Thiếu mã đề tài"]);
            http_response_code(400);
            exit;
        }

        $deTai->ma_dtcs = $_DELETE['ma_dtcs'];

        if ($deTai->delete()) {
            echo json_encode(["message" => "Đề tài và file trên Google Drive đã được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa đề tài hoặc file thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
