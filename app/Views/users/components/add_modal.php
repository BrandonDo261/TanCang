<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-dark" id="addUserModalLabel">
                    <i class="fas fa-user-plus text-primary me-2"></i>Thêm Tài Khoản Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="index.php?page=store_user" method="POST" onsubmit="return validateAddUserForm()"> 
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" autocomplete="new-password" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nhóm quyền Zone <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <option value="" disabled selected>-- Chọn nhóm quyền --</option>
                                <?php if(!empty($allRoles)): ?>
                                    <?php foreach($allRoles as $role): ?>
                                        <option value="<?= $role['id'] ?>">
                                            <?= htmlspecialchars($role['role_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Xác nhận tạo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>