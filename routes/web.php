<?php
// Đường dẫn: /routes/web.php

// Lấy tham số page, mặc định là home
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Định tuyến đến các Controller đã có của bạn
switch ($page) {
    case 'login':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->showLogin();
        break;

    case 'register':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->showRegister();
        break;

    case 'submit_register':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->handleRegister();
        break;

    case 'home':
        require_once 'app/Views/home/index.php';
        break;

    case 'profile':
        require_once 'app/Controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->index();
        break;
    
    case 'logout':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
        break;

    case 'orders':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->index();
        break;

    case 'delete_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->delete();
        break;

    case 'edit_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->edit();
        break;

    case 'reject_old_price':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->rejectOldPrice();
        break;
  
    case 'users':
        require_once 'app/Controllers/UserController.php';
        $userCtrl = new UserController();
        $userAction = $_GET['action'] ?? '';
        if ($userAction === 'toggle_status') {
            $userCtrl->toggleStatus();
        } elseif ($userAction === 'delete') {
            $userCtrl->delete();
        } else {
            $userCtrl->index();
        }
        break;
    
    case 'pricing':
        require_once 'app/Controllers/PricingController.php';
        $pricingController = new PricingController();
        $pricingController->index();
        break;
    case 'update_pricing':
        require_once 'app/Controllers/PricingController.php';
        $pricingController = new PricingController();
        $pricingController->update();
        break;

    case 'update_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->update();
        break;
    
    case 'change_status':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->changeStatus();
        break;

    // --- QUẢN LÝ PHÂN QUYỀN ---
    case 'roles':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->index();
        break;
        
    case 'store_role':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->store();
        break;
    case 'delete_role':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->delete();
        break;

    case 'export':
        require_once 'config/database.php';
        require_once __DIR__ . '/app/Export/BaseExport.php';
        require_once __DIR__ . '/app/Export/OrderExport.php';
        require_once __DIR__ . '/app/Controllers/ExportController.php';
        
        $controller = new \App\Controllers\ExportController($pdo);
        $action = $_GET['action'] ?? '';
        if ($action === 'order') {
            $controller->order();
        }
        break;

    case 'store_user':
        require_once 'app/Controllers/UserController.php';
        $userController = new UserController();
        $userController->store();
        break;
        
    case 'update_user':
        require_once 'app/Controllers/UserController.php';
        $userController = new UserController();
        $userController->update();
        break;
        
    case 'reports':
        require_once 'config/database.php';
        require_once 'app/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->index(); // Hiển thị giao diện bộ lọc ngày
        break;

    case 'export_report':
        require_once 'config/database.php';
        require_once 'app/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->exportExcel(); // Thực thi tải file Excel về máy
        break;

    default:
        echo "404 - Trang không tồn tại";
        break;
}