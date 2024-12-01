<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/HoSoDTCS.php';

$database = new Database();
$db = $database->getConn();
$hoSoDTCS = new HoSoDTCS($db);

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
            $stmt = $hoSoDTCS->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaHoSo = isset($_GET['MaHoSo']) ? $_GET['MaHoSo'] : null;
            if (!$MaHoSo) {
                echo json_encode(["message" => "Thiếu mã hồ sơ"]);
                http_response_code(400);
                exit;
            }
            $hoSoDTCS->MaHoSo = $MaHoSo;
            $data = $hoSoDTCS->getOne();
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
        if (!isset($data->MaHoSo, $data->NgayNop, $data->FileHoSo, $data->TrangThai, $data->MaKhoa)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoSoDTCS->MaHoSo = $data->MaHoSo;
        $hoSoDTCS->NgayNop = $data->NgayNop;
        $hoSoDTCS->FileHoSo = $data->FileHoSo;
        $hoSoDTCS->TrangThai = $data->TrangThai;
        $hoSoDTCS->MaKhoa = $data->MaKhoa;

        if ($hoSoDTCS->add()) {
            echo json_encode(["message" => "Thêm hồ sơ đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Thêm hồ sơ đào tạo cơ sở thất bại"]);
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
        if (!isset($_PUT['MaHoSo'], $_PUT['NgayNop'], $_PUT['FileHoSo'], $_PUT['TrangThai'], $_PUT['MaKhoa'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $hoSoDTCS->MaHoSo = $_PUT['MaHoSo'];
        $hoSoDTCS->NgayNop = $_PUT['NgayNop'];
        $hoSoDTCS->FileHoSo = $_PUT['FileHoSo'];
        $hoSoDTCS->TrangThai = $_PUT['TrangThai'];
        $hoSoDTCS->MaKhoa = $_PUT['MaKhoa'];

        if ($hoSoDTCS->update()) {
            echo json_encode(["message" => "Cập nhật hồ sơ đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật hồ sơ đào tạo cơ sở thất bại"]);
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

        $hoSoDTCS->MaHoSo = $data->MaHoSo;

        if ($hoSoDTCS->delete()) {
            echo json_encode(["message" => "Xóa hồ sơ đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Xóa hồ sơ đào tạo cơ sở thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
