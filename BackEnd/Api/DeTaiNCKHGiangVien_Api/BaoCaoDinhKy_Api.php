<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/BaoCaoDinhKy.php';

$database = new Database();
$db = $database->getConn();
$baoCao = new BaoCaoDinhKy($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả báo cáo
        $result = $baoCao->getAllReports();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy báo cáo theo MaDeTaiNCKHGV
        if (isset($_GET['maDeTai'])) {
            $result = $baoCao->getReportByMaDeTai($_GET['maDeTai']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy báo cáo với mã đề tài này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài (maDeTai)."]);
        }
        break;

    case 'POST':
        // Thêm báo cáo mới
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['NoiDungBaoCao'], $data['NgayNop'], $data['FileBaoCao'], $data['MaDeTaiNCKHGV'])) {
            if ($baoCao->addReport($data['NoiDungBaoCao'], $data['NgayNop'], $data['FileBaoCao'], $data['MaDeTaiNCKHGV'])) {
                echo json_encode(["message" => "Thêm báo cáo thành công."]);
            } else {
                echo json_encode(["message" => "Thêm báo cáo thất bại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin báo cáo để tạo."]);
        }
        break;

    case 'PUT':
        // Cập nhật báo cáo theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'], $data['NoiDungBaoCao'], $data['NgayNop'], $data['FileBaoCao'])) {
            if ($baoCao->updateReportByMaDeTai($data['MaDeTaiNCKHGV'], $data['NoiDungBaoCao'], $data['NgayNop'], $data['FileBaoCao'])) {
                echo json_encode(["message" => "Cập nhật báo cáo thành công."]);
            } else {
                echo json_encode(["message" => "Cập nhật thất bại hoặc MaDeTaiNCKHGV không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa báo cáo theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'])) {
            if ($baoCao->deleteReportByMaDeTai($data['MaDeTaiNCKHGV'])) {
                echo json_encode(["message" => "Xóa báo cáo thành công."]);
            } else {
                echo json_encode(["message" => "Xóa thất bại hoặc MaDeTaiNCKHGV không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu MaDeTaiNCKHGV để xóa."]);
        }
        break;

    default:
        echo json_encode(["message" => "Action không hợp lệ."]);
        break;
}
?>
