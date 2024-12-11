<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DonViDoiTac.php';

$database = new Database();
$db = $database->getConn();
$doiTac = new DonViDoiTac($db);

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
            $stmt = $doiTac->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDoiTac = isset($_GET['MaDoiTac']) ? $_GET['MaDoiTac'] : null;
            if (!$MaDoiTac) {
                echo json_encode(["message" => "Thiếu mã đối tác"]);
                http_response_code(400);
                exit;
            }
            $doiTac->MaDoiTac = $MaDoiTac;
            $data = $doiTac->getOne();
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
        if (!isset($data->MaDoiTac, $data->TenDoiTac, $data->SdtDoiTac, $data->EmailDoiTac, $data->DiaChiDoiTac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doiTac->MaDoiTac = $data->MaDoiTac;
        $doiTac->TenDoiTac = $data->TenDoiTac;
        $doiTac->SdtDoiTac = $data->SdtDoiTac;
        $doiTac->EmailDoiTac = $data->EmailDoiTac;
        $doiTac->DiaChiDoiTac = $data->DiaChiDoiTac;

        if ($doiTac->add()) {
            echo json_encode(["message" => "Thêm đối tác thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đối tác thất bại"]);
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
        if (!isset($_PUT['MaDoiTac'], $_PUT['TenDoiTac'], $_PUT['SdtDoiTac'], $_PUT['EmailDoiTac'], $_PUT['DiaChiDoiTac'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doiTac->MaDoiTac = $_PUT['MaDoiTac'];
        $doiTac->TenDoiTac = $_PUT['TenDoiTac'];
        $doiTac->SdtDoiTac = $_PUT['SdtDoiTac'];
        $doiTac->EmailDoiTac = $_PUT['EmailDoiTac'];
        $doiTac->DiaChiDoiTac = $_PUT['DiaChiDoiTac'];

        if ($doiTac->update()) {
            echo json_encode(["message" => "Cập nhật đối tác thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật đối tác thất bại"]);
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
        if (!isset($data->MaDoiTac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doiTac->MaDoiTac = $data->MaDoiTac;

        if ($doiTac->delete()) {
            echo json_encode(["message" => "Xóa đối tác thành công"]);
        } else {
            echo json_encode(["message" => "Xóa đối tác thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
