<div class="modal fade" id="rejectOldPriceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <form action="<?= $baseUrl ?>/index.php?page=reject_old_price" method="POST">
                
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-exclamation-triangle fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0">Từ chối đơn hàng giá cũ</h5>
                            <p class="text-muted small mb-0">Hành động này áp dụng cho toàn bộ đơn đang chờ.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-4 me-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pt-3 pb-4">
                    <div class="bg-light rounded p-3 mb-3 border" style="border-left: 4px solid #f59e0b !important;">
                        <span class="small text-dark fw-medium"><i class="fas fa-info-circle text-warning me-1"></i> Lưu ý:</span>
                        <span class="small text-muted">Lý do này sẽ được ghi vào hệ thống để các nhà xe/hãng tàu kiểm tra lại báo giá.</span>
                    </div>

                    <div>
                        <label for="rejectReason" class="form-label text-dark small fw-bold">Lý do chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control bg-light" id="rejectReason" name="reject_reason" rows="3" required placeholder="Ví dụ: Bảng giá tháng 4 đã hết hiệu lực, vui lòng cập nhật báo giá mới..." style="border-radius: 10px; resize: none; border: 1px solid #e2e8f0;"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill border shadow-sm" data-bs-dismiss="modal">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-paper-plane me-1"></i> Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rejectModal = document.getElementById('rejectOldPriceModal');
        if (rejectModal) {
            // "Bốc" modal ra khỏi các thẻ div bọc ngoài và ném thẳng vào <body>
            document.body.appendChild(rejectModal);
        }
    });
</script>