<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . "/../../Model/ThamDinhBaiBaoModel/TacGiaBaiBaoSinhVien.php";

$database = new Database();
$db = $database->getConn();

$tacGia = new TacGiaBaiBaoSinhVien($db);
$data = json_decode(file_get_contents("php://input"));
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $tacGia->MaSinhVien = $data->MaSinhVien;
        $tacGia->TenSinhVien = $data->TenSinhVien;
        $tacGia->EmailSV = $data->EmailSV;
        $tacGia->sdtSV = $data->sdtSV;
        $tacGia->MaTacGia = $data->MaTacGia;
        $tacGia->VaiTro = $data->VaiTro;
        $tacGia->MaBaiBao = $data->MaBaiBao;

        if ($tacGia->add()) {
            echo json_encode(["message" => "Record created successfully."]);
        } else {
            echo json_encode(["message" => "Failed to create record."]);
        }
        break;

    case 'get':
        $stmt = $tacGia->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $records = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $record_item =  ["MaSinhVien" => $MaSinhVien,
                    "TenSinhVien" => $TenSinhVien,
                    "EmailSV" => $EmailSV,
                    "sdtSV" => $sdtSV,
                    "VaiTro" => $VaiTro,
                    "MaBaiBao" => $MaBaiBao];
                $records[] = $record_item;
            }
            echo json_encode(["TacGiaSinhVien" =>$records],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No records found."]);
        }
        break;

    case 'update':
        $tacGia->MaSinhVien = $data->MaSinhVien;
        $tacGia->TenSinhVien = $data->TenSinhVien;
        $tacGia->EmailSV = $data->EmailSV;
        $tacGia->sdtSV = $data->sdtSV;
        $tacGia->MaTacGia = $data->MaTacGia;
        $tacGia->VaiTro = $data->VaiTro;
        $tacGia->MaBaiBao = $data->MaBaiBao;

        if ($tacGia->update()) {
            echo json_encode(["message" => "Record updated successfully."]);
        } else {
            echo json_encode(["message" => "Failed to update record."]);
        }
        break;

    case 'delete':
        $tacGia->MaTacGia = $data->MaTacGia;
        $tacGia->MaSinhVien = $data->MaSinhVien;

        if ($tacGia->delete()) {
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
