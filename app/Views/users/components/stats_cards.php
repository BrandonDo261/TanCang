<div class="row mb-4">
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-primary border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3"><i class="fas fa-users fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0"><?= $stats['total'] ?? 0 ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tổng số thành viên</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-success border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3"><i class="fas fa-user-check fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0"><?= $stats['active'] ?? 0 ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Đang hoạt động</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-danger border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle me-3"><i class="fas fa-user-lock fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0 text-danger"><?= $stats['locked'] ?? 0 ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tài khoản bị khóa</small>
                </div>
            </div>
        </div>
    </div>
</div>