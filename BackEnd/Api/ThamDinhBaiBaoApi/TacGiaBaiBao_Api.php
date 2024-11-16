<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/ThamDinhBaiBaoModel/TacGiaBaiBao.php';

$database = new Database();
$db = $database->getConn();
$tacgia = new TacGiaBaiBao($db);

// Lấy phương thức HTTP và tham số `action`
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Kiểm tra tham số `action`
if (!$action) {
    echo json_encode(["message" => "Yêu cầu không hợp lệ: thiếu tham số action"]);
    http_response_code(400);
    exit;
}

switch ($method) {
    case 'GET':
        if ($action === "get") {
            $stmt = $tacgia->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "Action không hợp lệ"]);
            http_response_code(400);
        }
        break;

    case 'add':
        if ($action !== "add") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức POST"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaTacGia, $data->MaBaiBao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tacgia->MaTacGia = $data->MaTacGia;
        $tacgia->MaBaiBao = $data->MaBaiBao;

        if ($tacgia->add()) {
            echo json_encode(["message" => "Thêm tác giả bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Thêm tác giả bài báo thất bại"]);
            http_response_code(500);
        }
        break;

    case 'update':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaTacGia, $data->MaBaiBao)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tacgia->MaTacGia = $data->MaTacGia;
        $tacgia->MaBaiBao = $data->MaBaiBao;

        if ($tacgia->update()) {
            echo json_encode(["message" => "Cập nhật thông tin thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật thông tin thất bại"]);
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
        if (!isset($data->MaTacGia)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $tacgia->MaTacGia = $data->MaTacGia;

        if ($tacgia->delete()) {
            echo json_encode(["message" => "Xóa tác giả bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Xóa tác giả bài báo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>