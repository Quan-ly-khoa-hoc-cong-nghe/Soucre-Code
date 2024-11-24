<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/HoiThaoKhoaHocModel/NguoiThamGia.php';

$database = new Database();
$db = $database->getConn();
$nguoiThamGia = new NguoiThamGia($db);

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
            $stmt = $nguoiThamGia->getAll();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
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
        if (!isset($data->MaNguoiThamGia, $data->TenNguoiThamGia, $data->Sdt, $data->Email, $data->HocHam, $data->HocVi, $data->FileHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nguoiThamGia->MaNguoiThamGia = $data->MaNguoiThamGia;
        $nguoiThamGia->TenNguoiThamGia = $data->TenNguoiThamGia;
        $nguoiThamGia->Sdt = $data->Sdt;
        $nguoiThamGia->Email = $data->Email;
        $nguoiThamGia->HocHam = $data->HocHam;
        $nguoiThamGia->HocVi = $data->HocVi;
        $nguoiThamGia->FileHoSo = $data->FileHoSo;

        if ($nguoiThamGia->add()) {
            echo json_encode(["message" => "Người tham gia được thêm thành công"]);
        } else {
            echo json_encode(["message" => "Thêm người tham gia thất bại"]);
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
        if (!isset($data->MaNguoiThamGia, $data->TenNguoiThamGia, $data->Sdt, $data->Email, $data->HocHam, $data->HocVi, $data->FileHoSo)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nguoiThamGia->MaNguoiThamGia = $data->MaNguoiThamGia;
        $nguoiThamGia->TenNguoiThamGia = $data->TenNguoiThamGia;
        $nguoiThamGia->Sdt = $data->Sdt;
        $nguoiThamGia->Email = $data->Email;
        $nguoiThamGia->HocHam = $data->HocHam;
        $nguoiThamGia->HocVi = $data->HocVi;
        $nguoiThamGia->FileHoSo = $data->FileHoSo;

        if ($nguoiThamGia->update()) {
            echo json_encode(["message" => "Người tham gia được cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật người tham gia thất bại"]);
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
        if (!isset($data->MaNguoiThamGia)) {
            echo json_encode(["message" => "Dữ liệu không đầy đủ"]);
            http_response_code(400);
            exit;
        }

        $nguoiThamGia->MaNguoiThamGia = $data->MaNguoiThamGia;

        if ($nguoiThamGia->delete()) {
            echo json_encode(["message" => "Người tham gia được xóa thành công"]);
        } else {
            echo json_encode(["message" => "Xóa người tham gia thất bại"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        http_response_code(405);
        break;
}
?>
