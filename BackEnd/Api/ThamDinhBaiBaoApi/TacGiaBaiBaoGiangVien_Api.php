<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . "/../../Model/ThamDinhBaiBaoModel/TacGiaBaiBaoGiangVien.php";


$database = new Database();
$db = $database->getConn();

$tacGia = new TacGiaBaiBaoGiangVien($db);
$data = json_decode(file_get_contents("php://input"));
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $tacGia->MaGV = $data->MaGV;
        $tacGia->HoTenGV = $data->HoTenGV;
        $tacGia->NgaySinhGV = $data->NgaySinhGV;
        $tacGia->EmailGV = $data->EmailGV;
        $tacGia->DiaChiGV = $data->DiaChiGV;
        $tacGia->DiemNCKH = $data->DiemNCKH;
        $tacGia->MaKhoa = $data->MaKhoa;
        $tacGia->MaTacGia = $data->MaTacGia;
        $tacGia->VaiTro = $data->VaiTro;
        $tacGia->MaBaiBao = $data->MaBaiBao;

        if ($tacGia->add()) {
            echo json_encode(["message" => "Record created successfully."]);
        } else {
            echo json_encode(["message" => "Failed to create record."]);
        }
        break;

    case 'read':
        $stmt = $tacGia->read();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode( ['TacGiaGiangVien'=> $records],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'delete':
        $tacGia->MaTacGia = $data->MaTacGia;
        $tacGia->MaGV = $data->MaGV;

        if ($tacGia->delete()) {
            echo json_encode(["message" => "Record deleted successfully."]);
        } else {
            echo json_encode(["message" => "Failed to delete record."]);
        }
        break;
        
        case 'update':
            $data = json_decode(file_get_contents("php://input"));
            $tacGia->MaGV = $data->MaGV;
            $tacGia->HoTenGV = $data->HoTenGV;
            $tacGia->NgaySinhGV = $data->NgaySinhGV;
            $tacGia->EmailGV = $data->EmailGV;
            $tacGia->DiaChiGV = $data->DiaChiGV;
            $tacGia->DiemNCKH = $data->DiemNCKH;
            $tacGia->MaKhoa = $data->MaKhoa;
            $tacGia->MaTacGia = $data->MaTacGia;
            $tacGia->VaiTro = $data->VaiTro;
            $tacGia->MaBaiBao = $data->MaBaiBao;
        
            if ($tacGia->update()) {
                echo json_encode(["message" => "Record updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update record."]);
            }
            break;
        
    default:
        echo json_encode(["message" => "Invalid action."]);
        break;
}
?>
