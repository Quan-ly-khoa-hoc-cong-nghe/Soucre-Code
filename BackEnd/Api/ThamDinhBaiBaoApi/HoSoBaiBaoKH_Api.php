<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/ThamDinhBaiBaoModel/HoSoBaiBaoKH.php';

$database = new Database();
$db = $database->getConn();

$hoSo = new HoSoBaiBaoKH($db);
$data = json_decode(file_get_contents("php://input"));
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $hoSo->MaHoSo = $data->MaHoSo;
        $hoSo->TrangThai = $data->TrangThai;
        $hoSo->MaNguoiDung = $data->MaNguoiDung;
        $hoSo->NgayNop = $data->NgayNop;
        $hoSo->MaTacGia = $data->MaTacGia;
        $hoSo->MaKhoa = $data->MaKhoa;

        if ($hoSo->add()) {
            echo json_encode(["message" => "Record created successfully."]);
        } else {
            echo json_encode(["message" => "Failed to create record."]);
        }
        break;

    case 'get':
        $stmt = $hoSo->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $records = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $record_item = [
                    "MaHoSo" => $MaHoSo,
                    "TrangThai" => $TrangThai,
                    "MaNguoiDung" => $MaNguoiDung,
                    "NgayNop" => $NgayNop,
                    "MaTacGia" => $MaTacGia,
                    "MaKhoa" => $MaKhoa
                ];
                $records[] = $record_item;
            }
            echo json_encode(["HoSoBaiBaoKhoaHoc" => $records],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No records found."]);
        }
        break;

    case 'update':
        $hoSo->MaHoSo = $data->MaHoSo;
        $hoSo->TrangThai = $data->TrangThai;
        $hoSo->MaNguoiDung = $data->MaNguoiDung;
        $hoSo->NgayNop = $data->NgayNop;
        $hoSo->MaTacGia = $data->MaTacGia;
        $hoSo->MaKhoa = $data->MaKhoa;

        if ($hoSo->update()) {
            echo json_encode(["message" => "Record updated successfully."]);
        } else {
            echo json_encode(["message" => "Failed to update record."]);
        }
        break;

    case 'delete':
        $hoSo->MaHoSo = $data->MaHoSo;

        if ($hoSo->delete()) {
            echo json_encode(["message" => "Record deleted successfully."]);
        } else {
            echo json_encode(["message" => "Failed to delete record."]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid action."]);
        break;
}
?>
