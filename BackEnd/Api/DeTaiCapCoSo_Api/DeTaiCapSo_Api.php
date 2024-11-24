<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/DeTaiCapSo.php';

$database = new Database();
$db = $database->getConn();
$deTaiCapSo = new DeTaiCapSo($db);

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
            $stmt = $deTaiCapSo->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $MaDTCS = isset($_GET['MaDTCS']) ? $_GET['MaDTCS'] : null;
            if (!$MaDTCS) {
                echo json_encode(["message" => "Thiếu mã đề tài cấp sở"]);
                http_response_code(400);
                exit;
            }
            $deTaiCapSo->MaDTCS = $MaDTCS;
            $data = $deTaiCapSo->getOne();
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
        if (!isset($data->MaDTCS, $data->TenDeTai, $data->NgayBatDau, $data->NgayKetThuc, $data->FileHopDong, $data->TrangThai, $data->MaHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $deTaiCapSo->MaDTCS = $data->MaDTCS;
        $deTaiCapSo->TenDeTai = $data->TenDeTai;
        $deTaiCapSo->NgayBatDau = $data->NgayBatDau;
        $deTaiCapSo->NgayKetThuc = $data->NgayKetThuc;
        $deTaiCapSo->FileHopDong = $data->FileHopDong;
        $deTaiCapSo->TrangThai = $data->TrangThai;
        $deTaiCapSo->MaHoSo = $data->MaHoSo;

        if ($deTaiCapSo->add()) {
            echo json_encode(["message" => "Thêm đề tài cấp sở thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đề tài cấp sở thất bại"]);
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
        if (!isset($_PUT['MaDTCS'], $_PUT['TenDeTai'], $_PUT['NgayBatDau'], $_PUT['NgayKetThuc'], $_PUT['FileHopDong'], $_PUT['TrangThai'], $_PUT['MaHoSo'])) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $deTaiCapSo->MaDTCS = $_PUT['MaDTCS'];
        $deTaiCapSo->TenDeTai = $_PUT['TenDeTai'];
        $deTaiCapSo->NgayBatDau = $_PUT['NgayBatDau'];
        $deTaiCapSo->NgayKetThuc = $_PUT['NgayKetThuc'];
        $deTaiCapSo->FileHopDong = $_PUT['FileHopDong'];
        $deTaiCapSo->TrangThai = $_PUT['TrangThai'];
        $deTaiCapSo->MaHoSo = $_PUT['MaHoSo'];

        if ($deTaiCapSo->update()) {
            echo json_encode(["message" => "Cập nhật đề tài cấp sở thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật đề tài cấp sở thất bại"]);
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

        $deTaiCapSo->MaDTCS = $data->MaDTCS;

        if ($deTaiCapSo->delete()) {
            echo json_encode(["message" => "Xóa đề tài cấp sở thành công"]);
        } else {
            echo json_encode(["message" => "Xóa đề tài cấp sở thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
