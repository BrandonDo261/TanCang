<?php
// Đường dẫn: api.php (thư mục gốc)
header('Content-Type: application/json');
session_start();

// Nạp kết nối Database
require_once 'config/database.php';
global $pdo;

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['unread_count' => 0, 'notifications' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

// === ĐỊNH TUYẾN (ROUTING) API XỬ LÝ THÔNG BÁO ===

if ($action === 'fetch') {
    // 1. Lấy danh sách 20 thông báo mới nhất của user đang đăng nhập
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Đếm số lượng thông báo chưa đọc (is_read = 0)
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmtCount->execute([$user_id]);
    $unread_count = $stmtCount->fetchColumn();

    // Trả về JSON cho Javascript
    echo json_encode([
        'unread_count'  => (int)$unread_count,
        'notifications' => $notifications
    ]);
} 
elseif ($action === 'mark_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đánh dấu 1 thông báo là đã đọc
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
    }
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'mark_all_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đánh dấu TẤT CẢ thông báo là đã đọc
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'delete_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xóa toàn bộ thông báo của user này
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE user_id = ?");
    $stmt->execute([$user_id]);
    echo json_encode(['status' => 'success']);
}