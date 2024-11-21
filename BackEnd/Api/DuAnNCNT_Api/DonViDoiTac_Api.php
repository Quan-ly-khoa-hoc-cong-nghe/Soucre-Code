<?php
header("Content-Type: application/json");
require_once __DIR__. '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DuAnNCNTModel/DonViDoiTac.php';

$database = new Database();
$db = $database->getConn();
$doitac = new DonViDoiTac($db);

// Lấy phương thức HTTP và tham số `action`
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
            $stmt = $doitac->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($action === "getOne") {
            $maDoiTac = isset($_GET['ma_doi_tac']) ? $_GET['ma_doi_tac'] : null;
            if (!$maDoiTac) {
                echo json_encode(["message" => "Thiếu mã đối tác"]);
                http_response_code(400);
                exit;
            }
            $doitac->ma_doi_tac = $maDoiTac;
            $stmt = $doitac->getOne();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
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
        if (!isset($data->ma_doi_tac, $data->ten_doi_tac, $data->sdt_doi_tac, $data->email_doi_tac, $data->dia_chi_doi_tac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doitac->ma_doi_tac = $data->ma_doi_tac;
        $doitac->ten_doi_tac = $data->ten_doi_tac;
        $doitac->sdt_doi_tac = $data->sdt_doi_tac;
        $doitac->email_doi_tac = $data->email_doi_tac;
        $doitac->dia_chi_doi_tac = $data->dia_chi_doi_tac;

        if ($doitac->add()) {
            echo json_encode(["message" => "Đối tác được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm đối tác thất bại"]);
            http_response_code(500);
        }
        break;

    case 'PUT':
        if ($action !== "put") {
            echo json_encode(["message" => "Action không hợp lệ cho phương thức PUT"]);
            http_response_code(400);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->ma_doi_tac, $data->ten_doi_tac, $data->sdt_doi_tac, $data->email_doi_tac, $data->dia_chi_doi_tac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doitac->ma_doi_tac = $data->ma_doi_tac;
        $doitac->ten_doi_tac = $data->ten_doi_tac;
        $doitac->sdt_doi_tac = $data->sdt_doi_tac;
        $doitac->email_doi_tac = $data->email_doi_tac;
        $doitac->dia_chi_doi_tac = $data->dia_chi_doi_tac;

        if ($doitac->update()) {
            echo json_encode(["message" => "Đối tác được cập nhật thành công"]);
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
        if (!isset($data->ma_doi_tac)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $doitac->ma_doi_tac = $data->ma_doi_tac;

        if ($doitac->delete()) {
            echo json_encode(["message" => "Đối tác được xóa thành công"]);
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
