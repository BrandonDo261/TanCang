<div class="dash-card border-0 shadow-sm" style="overflow: visible;">
    <div class="table-responsive" style="overflow: visible;">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 13px;">THÔNG TIN NGƯỜI DÙNG</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">VAI TRÒ / QUYỀN</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">LIÊN HỆ</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">TRẠNG THÁI</th>
                    <th class="py-3 text-center text-muted fw-bold" style="font-size: 13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)): ?>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=random&color=fff&bold=true" class="rounded-circle me-3 shadow-sm" width="45" height="45">
                                <div>
                                    <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($user['full_name']) ?></div>
                                    <div class="text-muted small">@<?= htmlspecialchars($user['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $roleClass = 'bg-secondary';
                                if($user['role_id'] == 1) $roleClass = 'bg-danger';
                                if($user['role_id'] == 2) $roleClass = 'bg-primary';
                                if($user['role_id'] == 4) $roleClass = 'bg-info';
                            ?>
                            <span class="badge <?= $roleClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $roleClass) ?> rounded-pill px-3 border border-<?= str_replace('bg-', '', $roleClass) ?> border-opacity-25">
                                <i class="fas fa-shield-alt me-1"></i> <?= htmlspecialchars($user['role_name'] ?? 'Chưa cấp quyền') ?>
                            </span>
                        </td>
                        <td>
                            <div class="small mb-1"><i class="far fa-envelope me-2 text-muted"></i><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?></div>
                            <div class="small"><i class="fas fa-phone-alt me-2 text-muted"></i><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></div>
                        </td>
                        <td>
                            <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                                <span class="d-flex align-items-center text-success fw-bold small"><span class="p-1 bg-success rounded-circle me-2 ripple-success"></span> Hoạt động</span>
                            <?php else: ?>
                                <span class="d-flex align-items-center text-danger fw-bold small"><span class="p-1 bg-danger rounded-circle me-2"></span> Đã khóa</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle border shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="z-index: 1050;">
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                           data-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>" data-fullname="<?= htmlspecialchars($user['full_name']) ?>" 
                                           data-email="<?= htmlspecialchars($user['email']) ?>" data-phone="<?= htmlspecialchars($user['phone']) ?>" data-role="<?= $user['role_id'] ?>" onclick="fillEditModal(this)">
                                        <i class="fas fa-user-edit me-2 text-primary"></i> Chỉnh sửa hồ sơ
                                    </a></li>
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#resetPassModal" 
                                           data-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>" onclick="fillResetModal(this)">
                                        <i class="fas fa-key me-2 text-warning"></i> Cấp lại mật khẩu
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                                        <li><a class="dropdown-item py-2 text-danger" href="<?= $baseUrl ?>/index.php?page=users&action=toggle_status&id=<?= $user['id'] ?>"><i class="fas fa-user-slash me-2"></i> Khóa tài khoản</a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item py-2 text-success" href="<?= $baseUrl ?>/index.php?page=users&action=toggle_status&id=<?= $user['id'] ?>"><i class="fas fa-user-check me-2"></i> Mở khóa tài khoản</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item py-2 text-danger" href="<?= $baseUrl ?>/index.php?page=users&action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Xác nhận xóa vĩnh viễn?')"><i class="fas fa-trash-alt me-2"></i> Xóa vĩnh viễn</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu người dùng</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>