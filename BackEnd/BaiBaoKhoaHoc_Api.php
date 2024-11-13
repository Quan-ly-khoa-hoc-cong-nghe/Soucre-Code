<?php
header("Content-Type: application/json; charset=UTF-8");
require_once("Database.php");

$db = new Database();
$conn = $db->getConn();

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Kết nối cơ sở dữ liệu thất bại'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Đọc dữ liệu đầu vào
$article = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        getAllArticle($conn);
        exit;
    case 'add':
        addArticle($conn);
        exit;
    case 'update':
        updateArticle($conn);
        exit;
    case 'delete':
        deleteArticle($conn);
        exit;
    default:
        echo json_encode(['message' => 'Error Action'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
}

// Hàm lấy tất cả bài báo
function getAllArticle($conn)
{
    try {
        $sql = "SELECT * FROM BaiBaoKhoaHoc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Lấy tất cả kết quả dưới dạng mảng
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ["BaiBaoKhoaHoc" => $articles];

        // Trả về kết quả dưới dạng JSON
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Lỗi truy vấn: " . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm thêm bài báos
function addArticle($conn)
{
    echo json_encode(["message" => "Hàm thêm bài báo chưa được triển khai"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// Hàm cập nhật bài báo
function updateArticle($conn)
{
    echo json_encode(["message" => "Hàm cập nhật bài báo chưa được triển khai"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// Hàm xóa bài báo
function deleteArticle($conn)
{
    echo json_encode(["message" => "Hàm xóa bài báo chưa được triển khai"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
