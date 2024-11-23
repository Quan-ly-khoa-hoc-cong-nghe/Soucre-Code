<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/DeTaiNCKHGiangVien/BaoCaoDinhKy.php';

$database = new Database();
$db = $database->getConn();
$detai = new BaoCaoDinhKy($db);

// Lấy action từ query string
$action = isset($_GET['action']) ? strtoupper(trim($_GET['action'])) : null;

if ($action === null) {
    echo json_encode(["message" => "Tham số 'action' không được cung cấp."]);
    exit;
}

switch ($action) {
    case 'GET':
        $stmt = $detai->read();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;

    case 'GET_ONE':
        if (!empty($_GET["MaDeTaiNCKHGV"])) {
            $detai->maDeTaiNCHKGV = $_GET["MaDeTaiNCKHGV"];
            $data = $detai->readOne();
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(["message" => "Đề tài NCKH không tồn tại."]);
            }
        } else {
            echo json_encode(["message" => "Tham số MaDeTaiNCKHGV không được cung cấp."]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->MaDeTaiNCKHGV) && !empty($data->TenDeTai)) {
            $detai->noiDungBaoCao = $data->noiDungBaoCao;
            $detai->ngayNop = $data->ngayNop;
            $detai->fileBaoCao = $data->fileBaoCao;
            $detai->maDeTaiNCHKGV = $data->maDeTaiNCHKGV;

            if ($detai->create()) {
                echo json_encode(["message" => "Đề tài NCKH được tạo thành công."]);
            } else {
                echo json_encode(["message" => "Không thể tạo đề tài NCKH."]);
            }
        } else {
            echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
       
            $detai->noiDungBaoCao = $data->noiDungBaoCao;
            $detai->ngayNop = $data->ngayNop;
            $detai->fileBaoCao = $data->fileBaoCao;
            $detai->maDeTaiNCHKGV = $data->maDeTaiNCHKGV;

            if ($detai->update()) {
                echo json_encode(["message" => "Đề tài NCKH được cập nhật thành công."]);
            } else {
                echo json_encode(["message" => "Không thể cập nhật đề tài NCKH."]);
            }
   
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
      
            $detai->maDeTaiNCHKGV = $data->maDeTaiNCHKGV;

            if ($detai->delete()) {
                echo json_encode(["message" => "Đề tài NCKH đã được xóa."]);
            } else {
                echo json_encode(["message" => "Không thể xóa đề tài NCKH."]);
            }
    
        break;

    default:
        echo json_encode(["message" => "Hành động không được hỗ trợ: $action."]);
        break;
}
?>
