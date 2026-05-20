<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-tags text-primary me-2"></i>Biểu giá dịch vụ</h4>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="dash-card p-4 mb-4">
            
            <?php if(isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success py-2 small fw-bold">
                    <i class="fas fa-check-circle me-1"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>/index.php?page=update_pricing" method="POST">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Loại Container</th>
                            <th>Đơn giá Nâng hạ (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prices as $row): ?>
                        <tr>
                            <td class="fw-bold text-center">Cont <?= $row['container_type'] ?> feet</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control text-end fw-bold text-primary" 
                                           name="prices[<?= $row['container_type'] ?>]" 
                                           value="<?= number_format($row['price'], 0, ',', '.') ?>" 
                                           onkeyup="formatCurrency(this)">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>

        </div>
    </div>
    
    <div class="col-md-6">
        <div class="alert alert-info border-0 shadow-sm rounded-3">
            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h6>
            <p class="small mb-1">- Đơn giá này sẽ được áp dụng trực tiếp để tính Doanh thu tổng ngoài trang Chủ.</p>
            <p class="small mb-0">- Sau khi thay đổi, hệ thống sẽ tự động cập nhật ngay lập tức.</p>
        </div>
    </div>
</div>

<script>
// Hàm tự động thêm dấu chấm khi gõ tiền (500000 -> 500.000)
function formatCurrency(input) {
    let value = input.value.replace(/\./g, '');
    if (!isNaN(value) && value !== '') {
        input.value = new Intl.NumberFormat('vi-VN').format(value);
    }
}
</script>

<?php require_once 'app/Views/layouts/footer.php'; ?>