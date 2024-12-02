<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/NhomNCNT.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomNCNT($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === "get") {
            $stmt = $nhom->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDuAn = isset($_GET['MaDuAn']) ? $_GET['MaDuAn'] : null; // Changed parameter name
            $VaiTro = isset($_GET['VaiTro']) ? $_GET['VaiTro'] : null; // Changed parameter name
            $MaGV = isset($_GET['MaGV']) ? $_GET['MaGV'] : null; // Changed parameter name
            if (!$MaDuAn || !$VaiTro || !$MaGV) {
                echo json_encode(["message" => "Thiếu mã dự án hoặc mã giảng viên"]);
                http_response_code(400);
                exit;
            }
            $nhom->MaDuAn = $MaDuAn;
            $nhom->VaiTro = $VaiTro;
            $nhom->MaGV = $MaGV;
            $data = $nhom->getOne();
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaDuAn,$data-> VaiTro, $data->MaGV)) { // Changed parameter name
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->MaDuAn = $data->MaDuAn; // Changed property name
        $nhom->VaiTro = $data->VaiTro;
        $nhom->MaGV = $data->MaGV; // Changed property name

        if ($nhom->add()) {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thất bại"]);
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
        if (!isset($_PUT['MaDuAn'], $_PUT['VaiTro'], $_PUT['MaGV'], $_PUT['MaGVMoi'])) { // Changed parameter name
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->MaDuAn = $_PUT['MaDuAn']; // Changed property name
        $nhom->VaiTro = $_PUT['VaiTro'];
        $nhom->MaGV = $_PUT['MaGV']; // Changed property name
        $MaGVMoi = $_PUT['MaGVMoi']; // Changed parameter name

        if ($nhom->update($MaGVMoi)) {
            echo json_encode(["message" => "Cập nhật thông tin thành viên trong nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật thông tin thành viên thất bại"]);
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
        if (!isset($data->MaDuAn,$data->VaiTro, $data->MaGV)) { // Changed parameter name
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->MaDuAn = $data->MaDuAn; // Changed property name
        $nhom->VaiTro = $data->VaiTro; // Changed property name
        $nhom->MaGV = $data->MaGV; // Changed property name

        if ($nhom->delete()) {
            echo json_encode(["message" => "Xóa thành viên khỏi nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Xóa thành viên khỏi nhóm thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
