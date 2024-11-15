<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once("../config/Database.php");

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
        addArticle($conn, $article);
        exit;
    case 'update':
        updateArticle($conn, $article);
        exit;
    case 'delete':
        deleteArticle($conn, $article);
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
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ["BaiBaoKhoaHoc" => $articles];

        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Lỗi truy vấn: " . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm thêm bài báo
function addArticle($conn, $article)
{
    try {
        $sql = "INSERT INTO BaiBaoKhoaHoc (tenBaiBaoKhoaHoc, urlBaiBaoKhoaHoc, ngayThamDinh) VALUES (:ten, :url, :ngay)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ten', $article['tenBaiBaoKhoaHoc']);
        $stmt->bindParam(':url', $article['urlBaiBaoKhoaHoc']);
        $stmt->bindParam(':ngay', $article['ngayThamDinh']);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Thêm bài báo thành công"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "Không thể thêm bài báo"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode(["message" => "Lỗi: " . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm cập nhật bài báo
function updateArticle($conn, $article)
{
    try {
        $sql = "UPDATE BaiBaoKhoaHoc SET tenBaiBaoKhoaHoc = :ten, urlBaiBaoKhoaHoc = :url, ngayThamDinh = :ngay WHERE maBaiBaoKhoaHoc = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $article['maBaiBaoKhoaHoc']);
        $stmt->bindParam(':ten', $article['tenBaiBaoKhoaHoc']);
        $stmt->bindParam(':url', $article['urlBaiBaoKhoaHoc']);
        $stmt->bindParam(':ngay', $article['ngayThamDinh']);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Cập nhật bài báo thành công"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "Không thể cập nhật bài báo"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode(["message" => "Lỗi: " . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

// Hàm xóa bài báo
function deleteArticle($conn, $article)
{
    try {
        $sql = "DELETE FROM BaiBaoKhoaHoc WHERE maBaiBaoKhoaHoc = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $article['maBaiBaoKhoaHoc']);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Xóa bài báo thành công"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "Không thể xóa bài báo"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode(["message" => "Lỗi: " . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
?>
