<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color: #2b2828;">
        <i class="fas fa-id-card me-2"></i> Thông tin người dùng
    </h4>
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?= $baseUrl ?? '' ?>/index.php?page=home"><i class="fas fa-home text-secondary"></i></a></li>
        <li class="breadcrumb-item active" aria-current="page">Hồ sơ cá nhân</li>
      </ol>
    </nav>
</div>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; overflow: hidden;">
    <div class="card-body px-4 pb-4 pt-5">
        
        <div class="text-center mb-4 pb-3 border-bottom border-light">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name'] ?? 'User') ?>&background=0284c7&color=fff&size=120" 
                 alt="Avatar" 
                 class="rounded-circle mb-3 shadow-sm" 
                 style="border: 5px solid #e0f2fe;">
            <h4 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($user['full_name'] ?? 'Chưa cập nhật') ?></h4>
            <p class="text-muted mb-0"><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-start">
                <tbody>
                    <tr>
                        <th class="text-muted fw-semibold w-25 border-light py-3 ps-4">Họ và tên</th>
                        <td class="border-light py-3 text-dark fw-medium">
                            <?= htmlspecialchars($user['full_name'] ?? 'Chưa cập nhật') ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3 ps-4">Chức vụ</th>
                        <td class="border-light py-3 text-dark">
                            <?= htmlspecialchars($user['role_name'] ?? 'Chưa cập nhật') ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3 ps-4">Trạng thái nhân viên</th>
                        <td class="border-light py-3">
                            <?php if (isset($user['is_active']) && $user['is_active'] == 1): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-circle text-primary me-1" style="font-size: 8px;"></i> Đang làm việc
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">
                                    <i class="fas fa-circle text-danger me-1" style="font-size: 8px;"></i> Tạm khóa
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3 ps-4">Email</th>
                        <td class="border-light py-3 text-dark">
                            <?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-0 py-3 ps-4">Số điện thoại</th>
                        <td class="border-0 py-3 text-dark">
                            <?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>