<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Quản lý Phân quyền (Roles)</h4>
    
    <button type="button" class="btn btn-primary fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addRoleModal">
        <i class="fas fa-plus me-1"></i> Thêm quyền mới
    </button>
</div>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success py-2 small fw-bold"><i class="fas fa-check-circle me-1"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger py-2 small fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
<?php endif; ?>

<div class="dash-card p-4">
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center mb-0" style="font-size: 13px;">
            <thead class="table-light">
                <tr>
                    <th width="60">ID</th>
                    <th width="120">Cấp độ Zone</th>
                    <th width="200">Tên Nhóm Quyền</th>
                    <th class="text-start">Mô tả giới hạn chức năng</th>
                    <th width="100">Tác vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($roles)): ?>
                    <?php foreach($roles as $role): ?>
                    <tr>
                        <td class="fw-bold text-muted"><?= $role['id'] ?></td>
                        
                        <td>
                            <?php if($role['level'] == 1): ?>
                                <span class="badge bg-danger px-2 py-1">Cấp 1 (Admin)</span>
                            <?php elseif($role['level'] == 2): ?>
                                <span class="badge bg-warning text-dark px-2 py-1">Cấp 2 (Quản lý)</span>
                            <?php elseif($role['level'] == 3): ?>
                                <span class="badge bg-primary px-2 py-1">Cấp 3 (Nhân viên)</span>
                            <?php elseif($role['level'] == 4): ?>
                                <span class="badge bg-info text-dark px-2 py-1">Cấp 4 (Kế toán)</span>
                            <?php endif; ?>
                        </td>
                        
                        <td><span class="fw-bold text-dark"><?= htmlspecialchars($role['role_name']) ?></span></td>
                        <td class="text-start text-muted"><?= htmlspecialchars($role['description'] ?? 'Chưa có mô tả') ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa quyền" style="width:28px; height:28px;" href="<?= $baseUrl ?>/index.php?page=delete_role&id=<?= $role['id'] ?>" onclick="return confirm('Xác nhận xóa nhóm quyền này?')">
                                <i class="fas fa-trash" style="font-size:11px;"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">Chưa có nhóm quyền nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 16px;"><i class="fas fa-plus-circle text-primary me-2"></i>Thêm Nhóm Quyền Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=store_role" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tên nhóm quyền <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Ví dụ: Điều độ viên bãi, Trưởng phòng..." required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Cấp độ Zone tương ứng <span class="text-danger">*</span></label>
                        <select name="level" class="form-select form-select-sm" required>
                            <option value="1">Cấp độ 1: Toàn quyền hệ thống (Admin)</option>
                            <option value="2">Cấp độ 2: Vào tất cả trừ phân quyền (Quản lý)</option>
                            <option value="3">Cấp độ 3: Chỉ Tiếp nhận / Duyệt lệnh (Nhân viên)</option>
                            <option value="4">Cấp độ 4: Chỉ xem Báo cáo & Biểu giá (Kế toán / Giám sát)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mô tả chi tiết chức năng</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Ghi rõ giới hạn phân khu của quyền này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold px-4"><i class="fas fa-save me-1"></i> Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>