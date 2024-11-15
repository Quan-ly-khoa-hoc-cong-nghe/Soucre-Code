<?php
header("Content-Type: application/json");
require_once __DIR__ .  '/../../config/Database.php';
require_once __DIR__ . '/../../Model/ThamDinhBaiBaoModel/ThamDinhBaiBao.php';

$database = new Database();
$db = $database->getConn();
$thamdinh = new ThamDinhBaiBao($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {  
    case 'GET':
        $stmt = $thamdinh->getAll();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;

    case 'ADD':
        $data = json_decode(file_get_contents("php://input"));
        $thamdinh->MaThamDinh = $data->MaThamDinh;
        $thamdinh->NgayThamDinh = $data->NgayThamDinh;
        $thamdinh->DanhGiaBaiBao = $data->DanhGiaBaiBao;
        $thamdinh->KetQua = $data->KetQua;
        $thamdinh->NhanXet = $data->NhanXet;

        if ($thamdinh->add()) {
            echo json_encode(["message" => "Thêm thẩm định thành công"]);
        } else {
            echo json_encode(["message" => "Thêm thẩm định thất bại"]);
        }
        break;

    case 'UPDATE':
        $data = json_decode(file_get_contents("php://input"));
        $thamdinh->MaThamDinh = $data->MaThamDinh;
        $thamdinh->NgayThamDinh = $data->NgayThamDinh;
        $thamdinh->DanhGiaBaiBao = $data->DanhGiaBaiBao;
        $thamdinh->KetQua = $data->KetQua;
        $thamdinh->NhanXet = $data->NhanXet;

        if ($thamdinh->update()) {
            echo json_encode(["message" => "Cập nhật thẩm định thành công"]);
        } else {
            echo json_encode(["message" => "Cập nhật thẩm định thất bại"]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $thamdinh->MaThamDinh = $data->MaThamDinh;

        if ($thamdinh->delete()) {
            echo json_encode(["message" => "Xóa thẩm định thành công"]);
        } else {
            echo json_encode(["message" => "Xóa thẩm định thất bại"]);
        }
        break;

    default:
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        break;
}
?>
