<div class="modal fade" id="resetPassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title fw-bold"><i class="fas fa-key me-2"></i>Cấp lại mật khẩu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=users" method="POST">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="id" id="reset_id">
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Tài khoản: <strong class="text-dark" id="reset_username"></strong></p>
                    <label class="form-label small fw-bold">Nhập mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="text" name="new_password" class="form-control text-center fw-bold fs-5" required placeholder="••••••••">
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning rounded-pill fw-bold"><i class="fas fa-check me-1"></i> Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>