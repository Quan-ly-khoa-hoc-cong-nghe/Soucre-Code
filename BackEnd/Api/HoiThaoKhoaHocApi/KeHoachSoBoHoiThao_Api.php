<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/KeHoachSoBoHoiThao.php';
$database = new Database();
$db = $database->getConn();
$kehoach = new KeHoachSoBo(db: $db);

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
            $stmt = $kehoach->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaKeHoachSoBo, $data->NgayGui, $data->FileKeHoach, $data->TrangThai, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->MaKeHoachSoBo = $data->MaKeHoachSoBo;
        $kehoach->NgayGui = $data->NgayGui;
        $kehoach->FileKeHoach = $data->FileKeHoach;
        $kehoach->TrangThai = $data->TrangThai;
        $kehoach->MaKhoa = $data->MaKhoa;

        if ($kehoach->add()) {
            echo json_encode(["message" => "Kế hoạch được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm kế hoạch thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaKeHoachSoBo, $data->NgayGui, $data->FileKeHoach, $data->TrangThai, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->MaKeHoachSoBo = $data->MaKeHoachSoBo;
        $kehoach->NgayGui = $data->NgayGui;
        $kehoach->FileKeHoach = $data->FileKeHoach;
        $kehoach->TrangThai = $data->TrangThai;
        $kehoach->MaKhoa = $data->MaKhoa;

        if ($kehoach->update()) {
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
        if (!isset($data->MaKeHoachSoBo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $kehoach->MaKeHoachSoBo = $data->MaKeHoachSoBo;

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
?>
