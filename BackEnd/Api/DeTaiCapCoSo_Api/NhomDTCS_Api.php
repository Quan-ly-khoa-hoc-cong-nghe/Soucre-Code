<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiCapCoSoModel/NhomDTCS.php';

$database = new Database();
$db = $database->getConn();
$nhom = new NhomDTCS($db);

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
            $maHoSo = isset($_GET['maDTCS']) ? $_GET['maDTCS'] : null;
            $maGV = isset($_GET['maGV']) ? $_GET['maGV'] : null;
            if (!$maHoSo || !$maGV) {
                echo json_encode(["message" => "Thiếu mã hồ sơ hoặc mã giảng viên"]);
                http_response_code(400);
                exit;
            }
            $nhom->MaDTCS = $maDTCS;
            $nhom->MaGV = $maGV;
            $data = $nhom->getOne();
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

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->maDTCS, $data->maGV)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->MaDTCS = $data->maDTCS;
        $nhom->MaGV = $data->maGV;

        if ($nhom->add()) {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm thành viên vào nhóm thất bại"]);
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
        if (!isset($data->maDTCS, $data->maGV)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nhom->MaDTCS = $data->maDTCS;
        $nhom->MaGV = $data->maGV;

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
?>
