<?php
header("Content-Type: application/json");
require_once '/../../Model/DeTaiNCKHGiangVien/HoSoNCKHGiangVien.php';

$method = $_SERVER['REQUEST_METHOD'];
$khoaHoSo = new KhoaHoSo();

switch ($method) {
    case 'GET':
        echo json_encode(["HoSoBaiBaoKhoaHoc"=>$khoaHoSo->getAll()],JSON_PRETTY_PRINT| JSON_UNESCAPED_UNICODE);
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(["HoSoBaiBaoKhoaHoc"=>$khoaHoSo->create($data)],JSON_PRETTY_PRINT| JSON_UNESCAPED_UNICODE);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(["HoSoBaiBaoKhoaHoc"=>$khoaHoSo->update($data)],JSON_PRETTY_PRINT| JSON_UNESCAPED_UNICODE);
        break;
    case 'DELETE':
        $MaKhoa = $_GET['MaKhoa'] ?? '';
        $MaHoSo = $_GET['MaHoSo'] ?? '';
        echo json_encode([$khoaHoSo->delete($MaKhoa, $MaHoSo)],JSON_PRETTY_PRINT| JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["message" => "Invalid request"]);
        break;
}
?>
