<?php require 'app/Views/layouts/header.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-file-excel mr-2"></i> Xuất Báo Cáo Lệnh Cảng (Excel)</h5>
        </div>
        <div class="card-body p-4">
            <form action="index.php?page=export_report" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="from_date" class="font-weight-bold text-secondary">Từ ngày:</label>
                        <input type="date" class="form-row form-control" id="from_date" name="from_date" 
                               value="<?= date('Y-m-01') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="to_date" class="font-weight-bold text-secondary">Đến ngày:</label>
                        <input type="date" class="form-row form-control" id="to_date" name="to_date" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                
                <div class="alert alert-info small mt-2">
                    <i class="fas fa-info-circle"></i> Hệ thống sẽ tự động tổng hợp toàn bộ danh sách lệnh, trạng thái duyệt và tính toán doanh thu trong khoảng thời gian trên vào file Excel.
                </div>

                <button type="submit" class="btn btn-success btn-block btn-lg mt-4 shadow-sm">
                    <i class="fas fa-download mr-2"></i> Tải Xuất Bản Excel (.xlsx)
                </button>
            </form>
        </div>
    </div>
</div>

<?php require 'app/Views/layouts/footer.php'; ?>