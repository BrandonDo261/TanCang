<?php 
    // KHAI BÁO ĐƯỜNG DẪN GỐC
    $baseUrl = 'http://localhost/Github/TanCang'; 
    // Lấy tên trang hiện tại để bôi đậm Menu
    $currentPage = $_GET['page'] ?? 'home'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Logistics Tân Cảng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/public/css/style.css">
</head>
<body>

<div class="wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-ship me-2"></i> TÂN<span class="text-info">CẢNG</span>
        </div>
        <div class="sidebar-menu">
            <p class="menu-label">BẢNG ĐIỀU KHIỂN</p>
            <a href="<?= $baseUrl ?>/index.php?page=home" class="<?= ($currentPage == 'home') ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> Tổng quan 
            </a>
            
            <p class="menu-label mt-3">NGHIỆP VỤ LOGISTICS</p>
            <a href="<?= $baseUrl ?>/index.php?page=orders" class="<?= ($currentPage == 'orders') ? 'active' : '' ?>">
                <i class="fas fa-clipboard-check"></i> Tiếp nhận lệnh / Đơn hàng
            </a>
            <a href="<?= $baseUrl ?>/index.php?page=pricing" class="<?= ($currentPage == 'pricing') ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Biểu giá dịch vụ
            </a>

            <p class="menu-label mt-3">QUẢN TRỊ HỆ THỐNG</p>
            <a href="<?= $baseUrl ?>/index.php?page=users" class="<?= ($currentPage == 'users') ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> Tài khoản nhân sự
            </a>
            <a href="<?= $baseUrl ?>/index.php?page=roles" class="<?= ($currentPage == 'roles') ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i> Nhóm quyền 
            </a>

            <p class="menu-label mt-3">HỆ THỐNG</p>
            <a href="<?= $baseUrl ?>/index.php?page=reports" class="<?= ($currentPage == 'reports') ? 'active' : '' ?>"><i class="fas fa-file-excel"></i> Báo cáo & Thống kê</a>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </div>
            <div class="topbar-right">
            <div class="icon-wrapper dropdown">
    <div data-bs-toggle="dropdown" style="cursor: pointer;">
        <i class="fas fa-bell"></i>
        <span class="badge-noti" id="notiCount" style="display: none;">0</span>
    </div>
    
    <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" id="notiDrop" style="width: 350px;">
        <h6 class="dropdown-header bg-light py-3 fw-bold border-bottom">THÔNG BÁO HỆ THỐNG</h6>
        
        <div id="notiList" style="max-height: 350px; overflow-y: auto;">
            <div class="text-center p-4 text-muted small">Đang tải dữ liệu...</div>
        </div>
        
        <div class="p-2 border-top d-flex justify-content-between bg-light">
            <button class="btn btn-sm btn-link text-decoration-none fw-bold" style="color: #0284c7; font-size: 13px;" onclick="markAllRead(event)">
                <i class="fas fa-check-double me-1"></i> Đã đọc hết
            </button>
            <button class="btn btn-sm btn-link text-decoration-none text-danger fw-bold" style="font-size: 13px;" onclick="deleteAllNoti(event)">
                <i class="fas fa-trash-alt me-1"></i> Xóa tất cả
            </button>
        </div>
    </div>
</div>

                <div class="user-profile dropdown">
                    
                    <span class="dropdown-toggle" data-bs-toggle="dropdown">Tài khoản</span>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="<?= $baseUrl ?>/index.php?page=profile"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= $baseUrl ?>/index.php?page=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="content-wrapper">
            <?php if(isset($_SESSION['success_msg']) || isset($_SESSION['error_msg'])): ?>
                <div class="container-fluid px-4 py-3">
                    <?php if(isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show small fw-bold" role="alert">
                            <i class="fas fa-check-circle me-1"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show small fw-bold" role="alert">
                            <i class="fas fa-exclamation-triangle me-1"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>