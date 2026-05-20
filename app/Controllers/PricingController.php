<?php

class PricingController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // Hiển thị giao diện cài đặt giá
    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 2) {
            // 1. Nạp tin nhắn gắt gỏng vào Session để View hứng hiện Popup
            $_SESSION['error_msg'] = "Truy cập bị từ chối! Tài khoản của bạn không có thẩm quyền vào phân khu này.";
            // 2. Lấy đường link của trang trước đó, nếu trình duyệt không lưu thì mặc định về trang home
            $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
            // 3. Đá người dùng quay lại nơi họ vừa đứng
            header("Location: " . $backUrl);
            exit();
        }
        $stmt = $this->pdo->query("SELECT * FROM logis_pricing ORDER BY container_type ASC");
        $prices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'app/Views/pricing/index.php';
    }

    // Lưu thay đổi giá khi bấm Submit
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prices'])) {
            
            // 1. Cập nhật giá mới vào Database
            foreach ($_POST['prices'] as $type => $price) {
                // Xóa dấu chấm phân cách ngàn để lưu vào DB (ví dụ: 500.000 -> 500000)
                $clean_price = str_replace('.', '', $price);
                
                $stmt = $this->pdo->prepare("UPDATE logis_pricing SET price = ? WHERE container_type = ?");
                $stmt->execute([$clean_price, $type]);
            }

            // ==========================================
            // 2. TRIGGER THÔNG BÁO: CẬP NHẬT BIỂU GIÁ
            // ==========================================
            // Gửi thông báo cho toàn bộ Admin, Manager và Staff (Role 1, 2, 3)
            $sqlGetStaff = "SELECT id FROM users WHERE role_id IN (1, 2, 3) AND is_active = 1";
            $stmtUsers = $this->pdo->query($sqlGetStaff);
            $staffList = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

            if ($staffList) {
                // A. Insert thông báo mới
                $sqlInsertNotif = "INSERT INTO notifications (user_id, title, message, is_read, created_at) 
                                   VALUES (?, ?, ?, 0, NOW())";
                $stmtNotif = $this->pdo->prepare($sqlInsertNotif);
                
                $title = "Cập nhật Biểu Giá Mới";
                $message = "Hệ thống vừa cập nhật một biểu giá nâng hạ/lưu bãi container mới. Vui lòng kiểm tra lại danh sách báo giá.";
                
                foreach ($staffList as $user) {
                    $stmtNotif->execute([$user['id'], $title, $message]);
                }

                // ==========================================
                // 3. AUTO-CLEANUP: DỌN RÁC THÔNG BÁO
                // ==========================================
                $sqlCleanup = "
                    DELETE FROM notifications 
                    WHERE user_id = ? 
                    AND id NOT IN (
                        SELECT id FROM (
                            SELECT id FROM notifications 
                            WHERE user_id = ? 
                            ORDER BY id DESC 
                            LIMIT 50
                        ) AS tmp
                    )
                ";
                $stmtCleanup = $this->pdo->prepare($sqlCleanup);
                foreach ($staffList as $user) {
                    $stmtCleanup->execute([$user['id'], $user['id']]);
                }
            }
            // ==========================================

            $_SESSION['success_msg'] = "Đã cập nhật biểu giá thành công!";
        }
        
        // Quay về trang danh sách giá
        header("Location: index.php?page=pricing");
        exit();
    }
}