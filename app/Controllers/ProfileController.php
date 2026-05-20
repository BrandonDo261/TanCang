<?php

class ProfileController {
    private $pdo;

    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // Lấy username của người dùng đang đăng nhập từ Session
        $username = $_SESSION['username'] ?? '';

        if (empty($username)) {
            header("Location: index.php?page=login");
            exit;
        }

        // Truy vấn thông tin người dùng từ bảng users và join với bảng roles
        $stmt = $this->pdo->prepare("
            SELECT 
            u.full_name, 
            u.email, 
            u.phone, 
            u.is_active, 
            r.role_name 
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "Không tìm thấy thông tin người dùng trong cơ sở dữ liệu.";
            exit;
        }

        // Truyền biến $user sang View để hiển thị
        require 'app/Views/profile/profile.php';
    }
}
?>