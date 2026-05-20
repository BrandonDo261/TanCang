<?php

class AuthController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // Hiển thị form đăng nhập
    public function showLogin() {
        require_once 'app/Views/auth/login.php';
    }

    // Xử lý khi user bấm nút Đăng nhập
    public function handleLogin() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha_input = $_POST['captcha'] ?? '';

        // 1. Kiểm tra CAPTCHA
        if (strtoupper($captcha_input) !== ($_SESSION['captcha_code'] ?? '')) {
            $error = "Mã xác nhận (CAPTCHA) không chính xác!";
            require_once 'app/Views/auth/login.php';
            return;
        }

        try {
            // 2. TRUY VẤN DATABASE CHUẨN XÁC THEO BẢNG CỦA BẠN
            // Lấy u.* từ bảng users, lấy role_name và level từ bảng roles
            $stmt = $this->pdo->prepare("SELECT u.*, r.role_name, r.level 
                                         FROM users u 
                                         LEFT JOIN roles r ON u.role_id = r.id 
                                         WHERE u.username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. KIỂM TRA MẬT KHẨU MÃ HÓA VÀ TRẠNG THÁI
            if ($user && password_verify($password, $user['password'])) {
                
                // Chặn nếu tài khoản bị khóa
                if (isset($user['is_active']) && $user['is_active'] == 0) {
                    $error = "Tài khoản của bạn đang bị khóa! Vui lòng liên hệ Admin.";
                    require_once 'app/Views/auth/login.php';
                    return;
                }

                // Lưu Session (Sử dụng đúng role_name và level)
                $_SESSION['user_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role_name'] ?? 'Không có quyền';
                $_SESSION['role_level'] = (int)($user['level'] ?? 4);

                // Cập nhật mốc thời gian vào last_login
                $updateStmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$user['id']]);

                // Đăng nhập thành công, bay thẳng vào trong
                header("Location: index.php?page=home");
                exit();
                
            } else {
                $error = "Tài khoản hoặc mật khẩu không chính xác!";
                require_once 'app/Views/auth/login.php';
            }
            
        } catch (PDOException $e) {
            // Hiển thị lỗi gốc nếu SQL có vấn đề (hữu ích cho việc debug)
            $error = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
            require_once 'app/Views/auth/login.php';
        }
    }

    public function showRegister() {
        // Truy vấn danh sách quyền từ Database để đổ ra thanh chọn Dropdown
        try {
            $stmtRoles = $this->pdo->query("SELECT id, role_name FROM roles ORDER BY level ASC");
            $allRoles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $allRoles = [];
        }
        
        require_once 'app/Views/auth/register.php';
    }

    // Xử lý dữ liệu và Lưu vào Database
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $passwordPlain = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($passwordPlain !== $confirmPassword) {
                $_SESSION['error_msg'] = "Các mật khẩu vừa nhập không khớp nhau. Thử lại.";
                header("Location: index.php?page=register");
                exit();
            }

            $password = password_hash($passwordPlain, PASSWORD_DEFAULT); // Mã hóa chuẩn Bcrypt
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $role_id = $_POST['role_id'];

            try {
                // Bơm dữ liệu vào các cột tương ứng, gán mặc định is_active = 1 và mốc thời gian NOW()
                $stmt = $this->pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, role_id, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
                $stmt->execute([$username, $password, $full_name, $email, $phone, $role_id]);
                
                $_SESSION['success_msg'] = "Tạo tài khoản thành công! Bạn có thể đăng nhập ngay.";
                header("Location: index.php?page=login");
                exit();
                
            } catch (PDOException $e) {
                // Nếu trùng username đã có trong bảng users
                $_SESSION['error_msg'] = "Tên đăng nhập này đã được sử dụng!";
                header("Location: index.php?page=register");
                exit();
            }
        }
    }

    // Xử lý Đăng xuất
    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }


}