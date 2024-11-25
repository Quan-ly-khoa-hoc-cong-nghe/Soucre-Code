<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/DuyetDeTaiSVModel/DeTaiNCKHSV.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$deTai = new DeTaiNCKHSV($conn);
$data = json_decode(file_get_contents('php://input'), true);  // Lấy dữ liệu từ body (POST, PUT)
$action = $_GET['action'] ?? '';  // Lấy action từ query string

function uploadFile($file, $targetDir)
{
    $fileName = basename($file['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        error_log("Upload thành công: " . $targetFilePath); // Log thành công
        return $fileName;
    } else {
        error_log("Upload thất bại: " . $file['name']); // Log thất bại
        return false;
    }
}


function callApi($api, $action, $data)
{
    $url = "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/" . $api . "?action=" . $action;
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return json_decode($result, true);
}

function errorResponse($message)
{
    echo json_encode(['success' => false, 'message' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function successResponse($message, $data = [])
{
    echo json_encode(array_merge(['success' => true, 'message' => $message], $data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'get': // Lấy tất cả đề tài
        $result = $deTai->readAll();
        echo json_encode(['DeTaiNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm mới đề tài
        error_log("Dữ liệu POST nhận được: " . print_r($_POST, true));
        error_log("Dữ liệu FILES nhận được: " . print_r($_FILES, true));
        $targetDir = __DIR__ . "/../../LuuFile/";

        // Kiểm tra dữ liệu đầu vào
        if (empty($_POST['MaDeTaiSV']) || empty($_POST['TenDeTai']) || empty($_POST['MoTa'])) {
            errorResponse("Vui lòng cung cấp đầy đủ thông tin: MaDeTaiSV, TenDeTai, MoTa.");
        }

        $missingFields = [];

        if (empty($_POST['MaDeTaiSV'])) {
            $missingFields[] = "MaDeTaiSV";
        }
        if (empty($_POST['TenDeTai'])) {
            $missingFields[] = "TenDeTai";
        }
        if (empty($_POST['MoTa'])) {
            $missingFields[] = "MoTa";
        }
        if (!empty($missingFields)) {
            errorResponse("Vui lòng cung cấp đầy đủ thông tin: " . implode(", ", $missingFields) . ".");
        }

        // Xử lý upload FileHopDong
        if (empty($_FILES['FileHopDong']['name'])) {
            errorResponse("File hợp đồng là bắt buộc.");
        } else {
            $fileHopDongName = uploadFile($_FILES['FileHopDong'], $targetDir);
            if (!$fileHopDongName) {
                errorResponse("Lỗi khi upload file hợp đồng.");
            }
        }

        // Xử lý upload FileKeHoach
        if (empty($_FILES['FileKeHoach']['name'])) {
            errorResponse("File kế hoạch là bắt buộc.");
        } else {
            $fileKeHoachName = uploadFile($_FILES['FileKeHoach'], $targetDir);
            if (!$fileKeHoachName) {
                errorResponse("Lỗi khi upload file kế hoạch.");
            }
        }

        // Gán dữ liệu đề tài
        $deTai->maDeTaiSV = $_POST['MaDeTaiSV'];
        $deTai->tenDeTai = $_POST['TenDeTai'];
        $deTai->moTa = $_POST['MoTa'];
        $deTai->trangThai = 'Chưa hoàn thành';
        $deTai->fileHopDong = $fileHopDongName;
        $deTai->maHoSo = $_POST['MaHoSo'];

        error_log("Dữ liệu đề tài trước khi thêm: " . print_r([
            'maDeTaiSV' => $deTai->maDeTaiSV,
            'tenDeTai' => $deTai->tenDeTai,
            'moTa' => $deTai->moTa,
            'trangThai' => $deTai->trangThai,
            'fileHopDong' => $deTai->fileHopDong,
            'maHoSo' => $deTai->maHoSo
        ], true));

        if ($deTai->add()) {
            successResponse("Thêm đề tài thành công.");
        } else {
            errorResponse("Không thể thêm đề tài. Dữ liệu không hợp lệ hoặc có lỗi xảy ra trong quá trình xử lý.");
        }
        // if ($deTai->add()) {
        //     // Gọi API thêm kế hoạch
        //     $keHoachData = [
        //         'NgayBatDau' => $_POST['NgayBatDau'],
        //         'NgayKetThuc' => $_POST['NgayKetThuc'],
        //         'KinhPhi' => $_POST['KinhPhi'],
        //         'FileKeHoach' => $fileKeHoachName,
        //         'MaDeTaiSV' => $_POST['MaDeTaiSV']
        //     ];
        //     $keHoachResponse = callApi('KeHoachNCKHSV_Api.php', 'add', $keHoachData);

        //     if (!$keHoachResponse['success']) {
        //         errorResponse("Lỗi khi thêm kế hoạch: " . $keHoachResponse['message']);
        //     }

        //     // Gọi API tạo nhóm
        //     $nhomData = [
        //         'MaNhomNCKHSV' => 1, // Hoặc logic tạo ID nhóm tự động
        //         'MaDeTaiSV' => $_POST['MaDeTaiSV']
        //     ];
        //     $nhomResponse = callApi('NhomNCKHSV_Api.php', 'add', $nhomData);

        //     if (!$nhomResponse['success']) {
        //         errorResponse("Lỗi khi tạo nhóm nghiên cứu: " . $nhomResponse['message']);
        //     }

        //     $MaNhomNCKHSV = $nhomResponse['MaNhomNCKHSV'] ?? 1; // ID nhóm từ phản hồi API

        //     // Gọi API thêm sinh viên vào nhóm
        //     if (!empty($_POST['SinhViens'])) {
        //         foreach ($_POST['SinhViens'] as $MaSinhVien) {
        //             $sinhVienData = [
        //                 'MaNhomNCKHSV' => $MaNhomNCKHSV,
        //                 'MaSinhVien' => $MaSinhVien
        //             ];
        //             $sinhVienResponse = callApi('SinhVienNCKHSV_Api.php', 'add', $sinhVienData);

        //             if (!$sinhVienResponse['success']) {
        //                 errorResponse("Lỗi khi thêm sinh viên vào nhóm: " . $sinhVienResponse['message']);
        //             }
        //         }
        //     }

        //     // Gọi API thêm giảng viên vào nhóm
        //     if (!empty($_POST['GiangViens'])) {
        //         foreach ($_POST['GiangViens'] as $MaGV) {
        //             $giangVienData = [
        //                 'MaNhomNCKHSV' => $MaNhomNCKHSV,
        //                 'MaGV' => $MaGV
        //             ];
        //             $giangVienResponse = callApi('GiangVienNCKHSV_Api.php', 'add', $giangVienData);

        //             if (!$giangVienResponse['success']) {
        //                 errorResponse("Lỗi khi thêm giảng viên vào nhóm: " . $giangVienResponse['message']);
        //             }
        //         }
        //     }

        //     successResponse("Thêm đề tài và các thông tin liên quan thành công.");
        // } else {
        //     errorResponse("Không thể thêm đề tài. Vui lòng kiểm tra dữ liệu.");
        // }
        break;

    case 'updateGroup': // Chỉnh sửa mã nhóm và mã đề tài
        $missingFields = [];

        // Kiểm tra các trường bắt buộc
        if (empty($data['maDeTaiSV'])) {
            $missingFields[] = 'Mã Đề Tài';
        }
        if (empty($data['maNhomNCKHSV'])) {
            $missingFields[] = 'Mã Nhóm';
        }

        // Kiểm tra các giá trị nhận được
        error_log('Dữ liệu nhận được: ' . print_r($data, true));  // Log để kiểm tra dữ liệu nhận được

        // Nếu có trường thiếu, trả về lỗi chi tiết
        if (!empty($missingFields)) {
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không đầy đủ: thiếu ' . implode(', ', $missingFields)
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        // Giữ nguyên mã đề tài và chỉ thay đổi mã nhóm
        $deTai->maDeTaiSV = $data['maDeTaiSV'];
        $deTai->maNhomNCKHSV = $data['maNhomNCKHSV'];

        // Cập nhật chỉ mã nhóm mà không thay đổi các thuộc tính khác
        if ($deTai->updateGroup()) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật mã nhóm thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật mã nhóm'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update': // Cập nhật đề tài
        $targetDir = __DIR__ . "/../../LuuFile/"; // Đường dẫn lưu file

        if (!empty($_FILES['FileHopDong']['name'])) {
            // Xử lý upload file hợp đồng
            $fileHopDongName = uploadFile($_FILES['FileHopDong'], $targetDir);
            if (!$fileHopDongName) {
                echo json_encode(['message' => 'Lỗi khi upload file hợp đồng'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
            }
            $deTai->fileHopDong = $fileHopDongName; // Lưu tên file vào cơ sở dữ liệu
        }

        $deTai->maDeTaiSV = $_POST['maDeTaiSV'];
        $deTai->tenDeTai = $_POST['tenDeTai'];
        $deTai->moTa = $_POST['moTa'];
        $deTai->trangThai = $_POST['trangThai'] ?? 'Đang làm';
        $deTai->maHoSo = $_POST['maHoSo'];
        $deTai->maNhomNCKHSV = $_POST['maNhomNCKHSV'];

        if ($deTai->update()) {
            echo json_encode(['message' => 'Cập nhật đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể cập nhật đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;



    case 'delete': // Xóa đề tài
        if (!empty($data['maDeTaiSV'])) {
            $deTai->maDeTaiSV = $data['maDeTaiSV'];

            if ($deTai->delete()) {
                echo json_encode(['message' => 'Xóa đề tài thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không thể xóa đề tài'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['message' => 'Dữ liệu không đầy đủ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
