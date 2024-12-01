<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__. '/../../Model/ThamDinhBaiBaoModel/HoSoBaiBaoKH.php';

$database = new Database();
$db = $database->getConn();
$hosobaibao = new HoSoBaiBaoKH($db);

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
            $stmt = $hosobaibao->getAll();
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
        if (!isset($data->MaHoSo, $data->TrangThai, $data->MaNguoiDung, $data->NgayNop, $data->fileHoSo, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;
        $hosobaibao->TrangThai = $data->TrangThai;
        $hosobaibao->NgayNop = $data->NgayNop;
        $hosobaibao->fileHoSo = $data-> fileHoSo;
        $hosobaibao->MaKhoa = $data->MaKhoa;

        if ($hosobaibao->add()) {
            echo json_encode(["message" => "Thêm hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Thêm hồ sơ bài báo thất bại"]);
            http_response_code(500);
        }
        break;
        case 'updateTrangThai':
            if (!empty($data->MaHoSo) && !empty($data->TrangThai)) {
                error_log("MaHoSo: " . $data->MaHoSo);  // Log dữ liệu nhận được
                error_log("TrangThai: " . $data->TrangThai);
        
                $hosobaibao->MaHoSo = $data->MaHoSo;
                $hosobaibao->TrangThai = $data->TrangThai;
                if ($hosobaibao->updateTrangThai()) {
                    echo json_encode(['message' => 'Cập nhật trạng thái hồ sơ thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(['message' => 'Không thể cập nhật trạng thái hồ sơ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(['message' => 'Thiếu mã hồ sơ hoặc trạng thái'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        

    case 'PUT':
        if ($action !== "update") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->MaHoSo, $data->TrangThai, $data->MaNguoiDung, $data->NgayNop, $data->fileHoSo, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;
        $hosobaibao->TrangThai = $data->TrangThai;
        $hosobaibao->NgayNop = $data->NgayNop;
        $hosobaibao->fileHoSo = $data->fileHoSo;
        $hosobaibao->MaKhoa = $data->MaKhoa;

        if ($hosobaibao->update()) {
            echo json_encode(["message" => "Cập nhật hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật hồ sơ bài báo thất bại"]);
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
        if (!isset($data->MaHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hosobaibao->MaHoSo = $data->MaHoSo;

        if ($hosobaibao->delete()) {
            echo json_encode(["message" => "Xóa hồ sơ bài báo thành công"]);
        } else {
            echo json_encode(["message" => "Xóa hồ sơ bài báo thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
