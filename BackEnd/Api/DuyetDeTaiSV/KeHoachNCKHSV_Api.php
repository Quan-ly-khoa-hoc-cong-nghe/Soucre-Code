<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/KeHoachNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$keHoach = new KeHoachNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

function uploadFile($file, $targetDir)
{
    $fileName = basename($file['name']); // Lấy tên file
    $targetFilePath = $targetDir . $fileName; // Đường dẫn lưu file
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return $fileName; // Trả về tên file nếu upload thành công
    }
    return false; // Trả về false nếu upload thất bại
}

switch ($action) {
    case 'get':
        $result = $keHoach->readAll();
        echo json_encode(['KeHoachNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm kế hoạch
        error_log("Dữ liệu nhận được: " . print_r($data, true));

        if (empty($data['NgayBatDau']) || empty($data['NgayKetThuc']) || empty($data['KinhPhi']) || empty($data['MaDeTaiSV']) || empty($data['FileKeHoach'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ thông tin: NgayBatDau, NgayKetThuc, KinhPhi, MaDeTaiSV, FileKeHoach'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Gán dữ liệu vào model
        $keHoach->NgayBatDau = $data['NgayBatDau'];
        $keHoach->NgayKetThuc = $data['NgayKetThuc'];
        $keHoach->KinhPhi = $data['KinhPhi'];
        $keHoach->FileKeHoach = $data['FileKeHoach']; // Lưu tên file vào cơ sở dữ liệu
        $keHoach->MaDeTaiSV = $data['MaDeTaiSV'];

        // Thêm kế hoạch
        $addKeHoachResult = $keHoach->add();
        if ($addKeHoachResult === true) {
            echo json_encode(['success' => true, 'message' => 'Thêm kế hoạch và đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            // Trả về thông báo lỗi nếu không thể thêm kế hoạch
            echo json_encode(['success' => false, 'message' => 'Không thể thêm kế hoạch: ' . $addKeHoachResult['error']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;


    case 'update': // Cập nhật kế hoạch
        $targetDir = __DIR__ . "/../../LuuFile/"; // Đường dẫn lưu file

        if (!empty($_FILES['FileKeHoach']['name'])) {
            // Xử lý upload file kế hoạch
            $fileKeHoachName = uploadFile($_FILES['FileKeHoach'], $targetDir);
            if (!$fileKeHoachName) {
                echo json_encode(['message' => 'Lỗi khi upload file kế hoạch'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
            }
            $keHoach->FileKeHoach = $fileKeHoachName; // Lưu tên file vào cơ sở dữ liệu
        }

        $keHoach->MaDeTaiSV = $_POST['MaDeTaiSV'];
        $keHoach->NgayBatDau = $_POST['NgayBatDau'];
        $keHoach->NgayKetThuc = $_POST['NgayKetThuc'];
        $keHoach->KinhPhi = $_POST['KinhPhi'];

        if ($keHoach->update()) {
            echo json_encode(['message' => 'Cập nhật kế hoạch thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể cập nhật kế hoạch'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;


    case 'delete':
        if (!empty($data['MaDeTaiSV'])) {
            $keHoach->MaDeTaiSV = $data['MaDeTaiSV'];
            if ($keHoach->delete()) {
                echo json_encode(['message' => 'Xóa kế hoạch thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa kế hoạch'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
