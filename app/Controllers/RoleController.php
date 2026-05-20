<?php

class RoleController {
    private $pdo;

    public function __construct() {
        // Nhúng file kết nối CSDL có sẵn của bạn
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // 1. Giao diện hiển thị danh sách quyền
    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 1) {
            // 1. Nạp tin nhắn gắt gỏng vào Session để View hứng hiện Popup
            $_SESSION['error_msg'] = "Truy cập bị từ chối! Tài khoản của bạn không có thẩm quyền vào phân khu này.";
            // 2. Lấy đường link của trang trước đó, nếu trình duyệt không lưu thì mặc định về trang home
            $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
            // 3. Đá người dùng quay lại nơi họ vừa đứng
            header("Location: " . $backUrl);
            exit();
        }
        // Lấy dữ liệu từ đúng bảng Roles của bạn
        $stmt = $this->pdo->query("SELECT * FROM Roles ORDER BY id ASC");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Gọi đến file View hiển thị
        require_once 'app/Views/roles/index.php';
    }

    // 2. Xử lý lưu quyền mới khi bấm nút Lưu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $level = (int)$_POST['level']; // Lấy cấp độ từ form
            $description = trim($_POST['description']);
            
            if (!empty($name) && $level >= 1 && $level <= 4) {
                try {
                    // Thêm cột level vào câu lệnh INSERT (sử dụng cột role_name)
                        $stmt = $this->pdo->prepare("INSERT INTO Roles (role_name, level, description) VALUES (?, ?, ?)");
                        $stmt->execute([$name, $level, $description]);
                    $_SESSION['success_msg'] = "Đã thêm nhóm quyền cấp độ " . $level . " thành công!";
                } catch (PDOException $e) {
                    $_SESSION['error_msg'] = "Tên quyền này đã tồn tại!";
                }
            }
            
            header("Location: index.php?page=roles");
            exit();
        }
    }

    // Xóa nhóm quyền
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            try {
                $stmt = $this->pdo->prepare("DELETE FROM Roles WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success_msg'] = "Đã xóa nhóm quyền thành công!";
            } catch (PDOException $e) {
                $_SESSION['error_msg'] = "Không thể xóa nhóm quyền: " . $e->getMessage();
            }
        }
        header("Location: index.php?page=roles");
        exit();
    }
}