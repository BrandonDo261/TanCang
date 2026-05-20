<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 0 có nghĩa là cookie chỉ tồn tại trong phiên làm việc hiện tại
session_set_cookie_params(0, '/'); 

// Thiết lập thời gian tự động đăng xuất sau 10 phút nếu không thao tác (tăng tính chuyên nghiệp)
ini_set('session.gc_maxlifetime', 600);

session_start();
// Nhúng file autoload của Composer
require_once 'vendor/autoload.php'; 

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

$publicPages = ['login', 'auth', 'register', 'submit_register'];

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // Nếu đã lưu thời gian hoạt động cuối, và thời gian đó cách hiện tại quá 10 phút (600 giây)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
        // Hủy toàn bộ phiên đăng nhập
        session_unset();     
        session_destroy();   
        
        // Chuyển hướng người dùng về trang đăng nhập với thông báo
        header("Location: index.php?timeout=1"); 
        exit();
    }
    // Nếu vẫn đang thao tác, cập nhật lại mốc thời gian hoạt động thành hiện tại
    $_SESSION['last_activity'] = time(); 
}

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Lấy tham số url ?page=...
$pageRequest = $_GET['page'] ?? '';

// Nếu gửi Form đăng nhập (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['captcha']) && $pageRequest === 'login') {
    require_once 'app/Controllers/AuthController.php';
    $auth = new AuthController();
    $auth->handleLogin();
    exit();
}

// Kiểm tra xem User đã đăng nhập chưa
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    if (in_array($pageRequest, $publicPages, true)) {
        // Cho phép các trang công khai khi chưa đăng nhập
        $page = $pageRequest;
    } else {
        require_once 'app/Controllers/AuthController.php';
        $auth = new AuthController();
        $auth->showLogin();
        exit();
    }
} else {
    // Nếu đã đăng nhập, chuyển hướng khỏi trang login/register
    if (in_array($pageRequest, ['login', 'register', 'submit_register'], true)) {
        header('Location: index.php?page=home');
        exit();
    }
    $page = $pageRequest ?: 'home';
}

// ============================================
// CÁC TRANG BÊN TRONG HỆ THỐNG (Sau khi đã login)
// ============================================

require_once 'routes/web.php';