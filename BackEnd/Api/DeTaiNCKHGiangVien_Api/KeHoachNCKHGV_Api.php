<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/KeHoachNCKHGV.php';

$database = new Database();
$db = $database->getConn();
$keHoach = new KeHoachNCKHGV($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

// Xử lý các action
switch ($action) {
    case 'GET':
        // Lấy tất cả kế hoạch
        $result = $keHoach->getAllPlans();
        echo json_encode($result);
        break;

    case 'GET_BY_ID':
        // Lấy kế hoạch theo MaDeTaiNCKHGV
        if (isset($_GET['maDeTai'])) {
            $result = $keHoach->getPlanByMaDeTai($_GET['maDeTai']);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["message" => "Không tìm thấy kế hoạch với mã đề tài này."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu mã đề tài (maDeTai)."]);
        }
        break;

        case 'POST':
            // Nhận dữ liệu từ client
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Kiểm tra các trường thông tin cần thiết
            if (isset($data['MaDeTaiNCKHGV'], $data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'])) {
                $maDeTaiNCKHGV = $data['MaDeTaiNCKHGV'];
                $ngayBatDau = $data['NgayBatDau'];
                $ngayKetThuc = $data['NgayKetThuc'];
                $kinhPhi = $data['KinhPhi'];
                $fileKeHoach = $data['FileKeHoach'];
                
                // Thực hiện thêm kế hoạch mới
                // Truyền các thông tin cần thiết, trong đó MaKeHoachNCKHGV sẽ được sinh tự động trong hàm addPlan
                if ($keHoach->addPlan($maDeTaiNCKHGV, $ngayBatDau, $ngayKetThuc, $kinhPhi, $fileKeHoach)) {
                    echo json_encode(["message" => "Thêm kế hoạch thành công."]);
                } else {
                    echo json_encode(["message" => "Thêm kế hoạch thất bại, có thể mã đề tài đã tồn tại."]);
                }
            } else {
                echo json_encode(["message" => "Thiếu thông tin cần thiết để thêm kế hoạch."]);
            }
            break;
        
        
    case 'PUT':
        // Cập nhật kế hoạch theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'], $data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'])) {
            if ($keHoach->updatePlanByMaDeTai($data['MaDeTaiNCKHGV'], $data['NgayBatDau'], $data['NgayKetThuc'], $data['KinhPhi'], $data['FileKeHoach'])) {
                echo json_encode(["message" => "Cập nhật kế hoạch thành công."]);
            } else {
                echo json_encode(["message" => "Cập nhật thất bại hoặc MaDeTaiNCKHGV không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Thiếu thông tin cần thiết để cập nhật."]);
        }
        break;

    case 'DELETE':
        // Xóa kế hoạch theo MaDeTaiNCKHGV
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['MaDeTaiNCKHGV'])) {
            if ($keHoach->deletePlanByMaDeTai($data['MaDeTaiNCKHGV'])) {
                echo json_encode(["message" => "Xóa kế hoạch thành công."]);
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
