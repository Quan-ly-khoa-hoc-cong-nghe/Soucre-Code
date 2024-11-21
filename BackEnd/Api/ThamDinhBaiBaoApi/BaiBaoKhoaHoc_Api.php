<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Model/ThamDinhBaiBaoModel/BaiBaoKhoaHoc.php';

$database = new Database();
$conn = $database->getConn();

if (!$conn) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$article = new BaiBaoKhoaHoc($conn);
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':  // Thêm case 'get' ở đây
        $result = $article->readAll();
        echo json_encode([ 'BaiBaoKhoaHoc' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'add':
        $article->tenBaiBaoKhoaHoc = $data['tenBaiBaoKhoaHoc'];
        $article->urlBaiBaoKhoaHoc = $data['urlBaiBaoKhoaHoc'];
        $article->NgayXuatBan = $data['NgayXuatBan'];

        if ($article->add()) {
            echo json_encode([ 'message' => 'Thêm bài báo thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể thêm bài báo'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'update':
        $article->maBaiBaoKhoaHoc = $data['maBaiBaoKhoaHoc'];
        $article->tenBaiBaoKhoaHoc = $data['tenBaiBaoKhoaHoc'];
        $article->urlBaiBaoKhoaHoc = $data['urlBaiBaoKhoaHoc'];
        $article->NgayXuatBan = $data['NgayXuatBan'];

        if ($article->update()) {
            echo json_encode([ 'message' => 'Cập nhật bài báo thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể cập nhật bài báo'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'delete':
        $article->maBaiBaoKhoaHoc = $data['maBaiBaoKhoaHoc'];

        if ($article->delete()) {
            echo json_encode(['message' => 'Xóa bài báo thành công'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'Không thể xóa bài báo'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        echo json_encode(['message' => 'Action không hợp lệ'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}

?>
