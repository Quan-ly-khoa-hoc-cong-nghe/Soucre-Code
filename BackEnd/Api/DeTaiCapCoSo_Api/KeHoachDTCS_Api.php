<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/KeHoachDTCS.php';

$database = new Database();
$db = $database->getConn();
$keHoachDTCS = new KeHoachDTCS($db);

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
            $stmt = $keHoachDTCS->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDTCS = isset($_GET['MaDTCS']) ? $_GET['MaDTCS'] : null;
            if (!$MaDTCS) {
                echo json_encode(["message" => "Thiếu mã đào tạo cơ sở"]);
                http_response_code(400);
                exit;
            }
            $keHoachDTCS->MaDTCS = $MaDTCS;
            $data = $keHoachDTCS->getOne();
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
        if (!isset($data->NgayBatDau, $data->NgayKetThuc, $data->KinhPhi, $data->FileKeHoach, $data->MaDTCS)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachDTCS->NgayBatDau = $data->NgayBatDau;
        $keHoachDTCS->NgayKetThuc = $data->NgayKetThuc;
        $keHoachDTCS->KinhPhi = $data->KinhPhi;
        $keHoachDTCS->FileKeHoach = $data->FileKeHoach;
        $keHoachDTCS->MaDTCS = $data->MaDTCS;

        if ($keHoachDTCS->add()) {
            echo json_encode(["message" => "Thêm kế hoạch đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Thêm kế hoạch đào tạo cơ sở thất bại"]);
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
        if (!isset($_PUT['NgayBatDau'], $_PUT['NgayKetThuc'], $_PUT['KinhPhi'], $_PUT['FileKeHoach'], $_PUT['MaDTCS'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachDTCS->NgayBatDau = $_PUT['NgayBatDau'];
        $keHoachDTCS->NgayKetThuc = $_PUT['NgayKetThuc'];
        $keHoachDTCS->KinhPhi = $_PUT['KinhPhi'];
        $keHoachDTCS->FileKeHoach = $_PUT['FileKeHoach'];
        $keHoachDTCS->MaDTCS = $_PUT['MaDTCS'];

        if ($keHoachDTCS->update()) {
            echo json_encode(["message" => "Cập nhật kế hoạch đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật kế hoạch đào tạo cơ sở thất bại"]);
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
        if (!isset($data->MaDTCS)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $keHoachDTCS->MaDTCS = $data->MaDTCS;

        if ($keHoachDTCS->delete()) {
            echo json_encode(["message" => "Xóa kế hoạch đào tạo cơ sở thành công"]);
        } else {
            echo json_encode(["message" => "Xóa kế hoạch đào tạo cơ sở thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
