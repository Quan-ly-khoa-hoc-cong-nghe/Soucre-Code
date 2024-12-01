<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
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

// Xử lý lỗi và phản hồi
function errorResponse($message)
{
    echo json_encode(['success' => false, 'message' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Xử lý phản hồi thành công
function successResponse($message)
{
    echo json_encode(['success' => true, 'message' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'get':
        $result = $deTai->readAll();
        echo json_encode(['DeTaiNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add': // Thêm mới đề tài
        error_log("Dữ liệu POST nhận được: " . print_r($_POST, true));
        error_log("Dữ liệu FILES nhận được: " . print_r($_FILES, true));

        // Đường dẫn lưu file
        $targetDir = __DIR__ . "/../../LuuFile/";

        // Kiểm tra dữ liệu đầu vào
        $missingFields = [];
        if (empty($_POST['TenDeTai'])) $missingFields[] = "TenDeTai";
        if (empty($_POST['MoTa'])) $missingFields[] = "MoTa";
        if (empty($_POST['NgayBatDau'])) $missingFields[] = "NgayBatDau";
        if (empty($_POST['NgayKetThuc'])) $missingFields[] = "NgayKetThuc";
        if (empty($_POST['KinhPhi'])) $missingFields[] = "KinhPhi";
        if (empty($_FILES['FileHopDong']['name'])) $missingFields[] = "File hợp đồng";
        if (empty($_FILES['FileKeHoach']['name'])) $missingFields[] = "File kế hoạch";

        if (!empty($missingFields)) {
            errorResponse("Vui lòng cung cấp đầy đủ thông tin: " . implode(", ", $missingFields) . ".");
        }

        // Xử lý upload FileHopDong
        $fileHopDongName = uploadFile($_FILES['FileHopDong'], $targetDir);
        if (!$fileHopDongName) errorResponse("Lỗi khi upload file hợp đồng.");

        // Xử lý upload FileKeHoach
        $fileKeHoachName = uploadFile($_FILES['FileKeHoach'], $targetDir);
        if (!$fileKeHoachName) errorResponse("Lỗi khi upload file kế hoạch.");

        // Gán dữ liệu đề tài (không cần truyền MaDeTaiSV và MaNhomNCKHSV)
        $deTai->tenDeTai = $_POST['TenDeTai'];
        $deTai->moTa = $_POST['MoTa'];
        $deTai->trangThai = 'Chưa hoàn thành';
        $deTai->fileHopDong = $fileHopDongName;
        $deTai->maHoSo = $_POST['MaHoSo'];

        error_log("Dữ liệu đề tài trước khi thêm: " . print_r([
            'tenDeTai' => $deTai->tenDeTai,
            'moTa' => $deTai->moTa,
            'trangThai' => $deTai->trangThai,
            'fileHopDong' => $deTai->fileHopDong,
            'maHoSo' => $deTai->maHoSo
        ], true));

        // Thêm đề tài và lấy mã tự sinh
        $maDeTaiSV = $deTai->add(); // Gọi phương thức để tự sinh mã đề tài

        if ($maDeTaiSV) {
            // Gọi API thêm kế hoạch
            $keHoachData = [
                'NgayBatDau' => $_POST['NgayBatDau'],
                'NgayKetThuc' => $_POST['NgayKetThuc'],
                'KinhPhi' => $_POST['KinhPhi'],
                'FileKeHoach' => $fileKeHoachName,
                'MaDeTaiSV' => $maDeTaiSV // Truyền mã đề tài đã được tự sinh vào khi thêm kế hoạch
            ];

            // Gọi API thêm kế hoạch
            $keHoachResponse = callApi('KeHoachNCKHSV_Api.php', 'add', $keHoachData);

            if (is_array($keHoachResponse) && isset($keHoachResponse['success']) && !$keHoachResponse['success']) {
                errorResponse("Lỗi khi thêm kế hoạch: " . $keHoachResponse['message']);
            }

            // Gọi API tạo nhóm và lấy MaNhomNCKHSV
            $nhomData = [
                'MaDeTaiSV' => $maDeTaiSV // Sử dụng MaDeTaiSV đã được tự động sinh
            ];
            $nhomResponse = callApi('NhomNCKHSV_Api.php', 'add', $nhomData);

            if (!$nhomResponse['success']) {
                errorResponse("Lỗi khi tạo nhóm nghiên cứu: " . $nhomResponse['message']);
            } else {
                // Nếu nhóm được tạo thành công, lấy MaNhomNCKHSV từ kết quả trả về
                $maNhomNCKHSV = $nhomResponse['MaNhomNCKHSV'];  // Lấy mã nhóm từ phản hồi

                error_log("Mã nhóm tạo thành công: " . $maNhomNCKHSV);

                if ($maNhomNCKHSV) {
                    // Thêm sinh viên vào nhóm nếu có danh sách sinh viên
                    if (!empty($_POST['SinhViens'])) {
                        foreach ($_POST['SinhViens'] as $MaSinhVien) {
                            $sinhVienData = [
                                'MaNhomNCKHSV' => $maNhomNCKHSV,
                                'MaSinhVien' => $MaSinhVien
                            ];
                            $sinhVienResponse = callApi('SinhVienNCKHSV_Api.php', 'add', $sinhVienData);

                            if (!isset($sinhVienResponse['success']) || !$sinhVienResponse['success']) {
                                error_log("Lỗi khi thêm sinh viên vào nhóm: " . ($sinhVienResponse['message'] ?? "Không nhận được phản hồi hợp lệ."));
                            }
                        }
                    }

                    // Thêm giảng viên vào nhóm nếu có danh sách giảng viên
                    if (!empty($_POST['GiangViens'])) {
                        foreach ($_POST['GiangViens'] as $MaGV) {
                            $giangVienData = [
                                'MaNhomNCKHSV' => $maNhomNCKHSV,
                                'MaGV' => $MaGV
                            ];
                            error_log("Dữ liệu gửi đến GiangVienNCKHSV_Api: " . print_r($giangVienData, true));
                            $giangVienResponse = callApi('GiangVienNCKHSV_Api.php', 'add', $giangVienData);

                            if (!isset($giangVienResponse['success']) || !$giangVienResponse['success']) {
                                error_log("Lỗi khi thêm giảng viên vào nhóm: " . ($giangVienResponse['message'] ?? "Không nhận được phản hồi hợp lệ."));
                            }
                        }
                    }

                    successResponse("Thêm đề tài và các thông tin liên quan thành công.");
                } else {
                    errorResponse("Không thể lấy MaNhomNCKHSV sau khi tạo nhóm.");
                }
            }
        } else {
            errorResponse("Không thể thêm đề tài. Vui lòng kiểm tra dữ liệu.");
        }
        break;

    case 'getDetailedInfo': // Action lấy tất cả các đề tài và thông tin chi tiết
        // Gọi phương thức 'getDetailedInfo' từ model
        $result = $deTai->getDetailedInfo();

        // Kiểm tra kết quả trả về từ model
        if (isset($result['error'])) {
            // Nếu có lỗi truy vấn, trả về lỗi
            echo json_encode(['error' => $result['error']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } elseif (isset($result['message'])) {
            // Nếu không có đề tài nào, trả về thông báo lỗi
            echo json_encode(['message' => $result['message']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            // Nếu có kết quả, trả về tất cả các đề tài và thông tin đi kèm
            echo json_encode(['DeTaiNCKHSV' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        if (!empty($data['MaDeTaiSV'])) {
            $deTai->maDeTaiSV = $data['MaDeTaiSV'];
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
