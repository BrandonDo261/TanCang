<?php

class UserController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 2) {
            // 1. Nạp tin nhắn vào Session để View hứng hiện Popup
            $_SESSION['error_msg'] = "Truy cập bị từ chối! Tài khoản của bạn không có thẩm quyền vào phân khu này.";
            // 2. Lấy đường link của trang trước đó, nếu trình duyệt không lưu thì mặc định về trang home
            $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
            // 3. chuyển người dùng quay lại nơi họ vừa đứng
            header("Location: " . $backUrl);
            exit();
        }
        // Lấy danh sách user kèm theo tên quyền
        $stmt = $this->pdo->query("SELECT u.*, r.role_name as role_name 
                                   FROM users u 
                                   LEFT JOIN Roles r ON u.role_id = r.id 
                                   ORDER BY u.id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // LẤY DANH SÁCH QUYỀN TỪ DB ĐỂ ĐỔ VÀO POPUP THÊM/SỬA
        $stmtRoles = $this->pdo->query("SELECT id, role_name FROM Roles ORDER BY level ASC");
        $allRoles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 1; })),
            'locked' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 0; }))
        ];

        require 'app/Views/users/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $passwordInput = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role_id = $_POST['role_id'];

            if ($passwordInput !== $confirmPassword) {
                header("Location: index.php?page=users&msg=password_mismatch");
                exit();
            }

            $password = password_hash($passwordInput, PASSWORD_DEFAULT);

            try {
                $stmt = $this->pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, role_id, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$username, $password, $full_name, $email, $phone, $role_id]);
            } catch (PDOException $e) {
                header("Location: index.php?page=users&msg=user_exists");
                exit();
            }
            header("Location: index.php?page=users&msg=added");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role_id = $_POST['role_id'];

            $stmt = $this->pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $role_id, $id]);
            
            $_SESSION['success_msg'] = "Cập nhật thông tin thành công!";
            header("Location: index.php?page=users");
            exit();
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_password, $id]);

            $_SESSION['success_msg'] = "Khôi phục mật khẩu thành công!";
            header("Location: index.php?page=users");
            exit();
        }
    }

    public function toggleStatus() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $this->pdo->prepare("SELECT is_active FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user !== false) {
                $newStatus = $user['is_active'] == 1 ? 0 : 1;
                $stmt = $this->pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
                $stmt->execute([$newStatus, $id]);

                $_SESSION['success_msg'] = $newStatus === 1 ? "Mở khóa tài khoản thành công!" : "Khóa tài khoản thành công!";
            }
        }
        header("Location: index.php?page=users");
        exit();
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success_msg'] = "Xóa tài khoản vĩnh viễn thành công!";
        }
        header("Location: index.php?page=users");
        exit();
    }
}