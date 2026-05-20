<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - TÂN CẢNG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { width: 1000%; max-width: 700px; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
        .login-header { background: #0284c7; color: white; padding: 30px 20px; text-align: center; }
        .login-body { padding: 30px; }
        .form-control { border-radius: 8px; padding: 10px 15px; }
        .form-control:focus { box-shadow: none; border-color: #0284c7; }
        .btn-login { background-color: #0284c7; color: white; font-weight: 600; border-radius: 8px; padding: 10px; transition: 0.3s; }
        .btn-login:hover { background-color: #0369a1; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h3 class="fw-bold mb-0"><i class="fas fa-ship me-2"></i> TÂN CẢNG</h3>
        <p class="mb-0 opacity-75 small mt-1">Hệ thống Quản trị Cảng biển Tân Cảng</p>
    </div>
    <div class="login-body">
        <!-- Nơi hiển thị thông báo lỗi từ Controller -->
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success small py-2"><i class="fas fa-check-circle"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger small py-2"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>

        <form action="index.php?page=login" method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Tài khoản</label>
                <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" autocomplete="new-password" required>
            </div>
            
            <!-- Phần CAPTCHA -->
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold ">Mã xác nhận</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="captcha" class="form-control" placeholder="Nhập mã bên cạnh" required style="width: 70%; height: 60px">
                    <!-- Gọi file captcha.php như một hình ảnh -->
                    <img src="captcha.php" alt="CAPTCHA" id="captchaImage" class="rounded border" style="height: 60px; cursor: pointer;" title="Click để đổi mã" onclick="this.src='captcha.php?'+Math.random();">
                    <button type="button" class="btn btn-light border" onclick="document.getElementById('captchaImage').src='captcha.php?'+Math.random();"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3">ĐĂNG NHẬP</button>
            
            <div class="text-center mt-4">
            <span class="small text-muted">Chưa có tài khoản nội bộ? </span>
                <a href="index.php?page=register" class="small fw-bold text-primary text-decoration-none">
                    Đăng ký ngay <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            
        </form>
    </div>
</div>

</body>
</html>