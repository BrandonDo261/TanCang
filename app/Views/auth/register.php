<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 550px;">
        <div class="card-header bg-primary text-white text-center py-4 rounded-top-4 border-0">
            <h4 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Tạo Tài Khoản</h4>
        </div>
        <div class="card-body p-4 px-md-5">
            
            <?php if(isset($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger py-2 small fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=submit_register" method="POST">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control form-control-sm border-secondary-subtle bg-light" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm border-secondary-subtle" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-sm border-secondary-subtle" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Họ và Tên đầy đủ <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-sm border-secondary-subtle" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm border-secondary-subtle">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control form-control-sm border-secondary-subtle">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Nhóm phân quyền (Zone) <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select form-select-sm border-secondary-subtle" required>
                        <option value="" disabled selected>-- Chọn nhóm quyền --</option>
                        <?php if(!empty($allRoles)): ?>
                            <?php foreach($allRoles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">Xác nhận tạo tài khoản</button>
                    <a href="index.php?page=login" class="btn btn-light fw-medium py-2 rounded-pill border small">Quay lại trang Đăng nhập</a>
                </div>
            </form>
            
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            form.addEventListener('submit', function(event) {
                if (password.value !== confirmPassword.value) {
                    event.preventDefault();
                    alert('Các mật khẩu vừa nhập không khớp nhau. Thử lại.');
                    confirmPassword.focus();
                }
            });
        });
    </script>
</body>
</html>