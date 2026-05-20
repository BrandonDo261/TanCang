<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-edit text-primary me-2"></i>Cập nhật Đơn hàng</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=home"><i class="fas fa-home text-primary"></i></a></li>
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=orders">Tiếp nhận đơn</a></li>
            <li class="breadcrumb-item active">Sửa đơn hàng</li>
        </ol>
    </nav>
</div>

<div class="dash-card p-4 mb-4">
    <form action="<?= $baseUrl ?>/index.php?page=update_order" method="POST">
        <input type="hidden" name="id" value="<?= $order['id'] ?>">
        
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label fw-bold small">Mã đơn hàng</label>
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($order['order_code']) ?>" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small">Tên người tạo <span class="text-danger">*</span></label>
                <input type="text" name="creator_name" class="form-control" value="<?= htmlspecialchars($order['creator_name']) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small">Loại tác vụ</label>
                <select name="action_type" class="form-select">
                    <option value="Hạ rỗng" <?= $order['action_type'] == 'Hạ rỗng' ? 'selected' : '' ?>>Hạ rỗng</option>
                    <option value="Cấp rỗng" <?= $order['action_type'] == 'Cấp rỗng' ? 'selected' : '' ?>>Cấp rỗng</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small">Trạng thái</label>
                <select name="status" class="form-select <?= $order['status'] == 'Chờ duyệt' ? 'bg-warning bg-opacity-25' : '' ?>">
                    <option value="Chờ duyệt" <?= $order['status'] == 'Chờ duyệt' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="Duyệt đồng ý" <?= $order['status'] == 'Duyệt đồng ý' ? 'selected' : '' ?>>Duyệt đồng ý</option>
                    <option value="Duyệt từ chối" <?= $order['status'] == 'Duyệt từ chối' ? 'selected' : '' ?>>Duyệt từ chối</option>
                    <option value="Đã thanh toán" <?= $order['status'] == 'Đã thanh toán' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="Đang làm hàng" <?= $order['status'] == 'Đang làm hàng' ? 'selected' : '' ?>>Đang làm hàng</option>
                    <option value="Hoàn thành" <?= $order['status'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold small">Depot <span class="text-danger">*</span></label>
                <input type="text" name="depot_name" class="form-control" value="<?= htmlspecialchars($order['depot_name']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small">Hãng tàu <span class="text-danger">*</span></label>
                <input type="text" name="shipping_line" class="form-control" value="<?= htmlspecialchars($order['shipping_line']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small">Số BL/DO/BKG</label>
                <input type="text" name="bl_do_bkg" class="form-control" value="<?= htmlspecialchars($order['bl_do_bkg']) ?>">
            </div>
        </div>

        <div class="mb-4 mt-4 border-top pt-3">
            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-cubes me-2"></i>Chi tiết Container</h6>
            
            <table class="table table-bordered table-sm" id="containerTable">
                <thead class="table-light text-center">
                    <tr>
                        <th>Loại Container</th>
                        <th width="150">Số lượng</th>
                        <th width="80">Xóa</th>
                    </tr>
                </thead>
                <tbody id="containerBody">
                    <?php if(empty($orderDetails)): ?>
                        <tr>
                            <td>
                                <select name="containers[0][pricing_id]" class="form-select form-select-sm">
                                    <option value="">-- Chọn Loại Cont --</option>
                                    <?php foreach($pricings as $price): ?>
                                        <option value="<?= $price['id'] ?>"><?= $price['container_type'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" min="1" name="containers[0][quantity]" class="form-control form-control-sm text-center"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($orderDetails as $index => $detail): ?>
                        <tr>
                            <td>
                                <select name="containers[<?= $index ?>][pricing_id]" class="form-select form-select-sm">
                                    <option value="">-- Chọn Loại Cont --</option>
                                    <?php foreach($pricings as $price): ?>
                                        <option value="<?= $price['id'] ?>" <?= $price['id'] == $detail['pricing_id'] ? 'selected' : '' ?>>
                                            <?= $price['container_type'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" min="1" name="containers[<?= $index ?>][quantity]" class="form-control form-control-sm text-center" value="<?= $detail['quantity'] ?>"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-success rounded-pill fw-bold" onclick="addContainer()"><i class="fas fa-plus me-1"></i> Thêm dòng Cont</button>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small">Ghi chú</label>
            <textarea name="note" class="form-control" rows="3"><?= htmlspecialchars($order['note']) ?></textarea>
        </div>

        <div class="text-end border-top pt-3">
            <a href="<?= $baseUrl ?>/index.php?page=orders" class="btn btn-secondary fw-bold rounded-pill px-4 me-2"><i class="fas fa-times me-1"></i>Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4"><i class="fas fa-save me-1"></i>Lưu Thay Đổi</button>
        </div>
    </form>
</div>

<script>
    let containerIndex = <?= isset($orderDetails) && count($orderDetails) > 0 ? count($orderDetails) : 1 ?>;
    
    let pricingOptions = `<option value="">-- Chọn Loại Cont --</option>`;
    <?php foreach($pricings as $price): ?>
        pricingOptions += `<option value="<?= $price['id'] ?>"><?= $price['container_type'] ?></option>`;
    <?php endforeach; ?>

    function addContainer() {
        const tbody = document.getElementById('containerBody');
        const tr = document.createElement('tr');
        
        tr.innerHTML = `
            <td>
                <select name="containers[${containerIndex}][pricing_id]" class="form-select form-select-sm" required>
                    ${pricingOptions}
                </select>
            </td>
            <td><input type="number" min="1" name="containers[${containerIndex}][quantity]" class="form-control form-control-sm text-center" required></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
        `;
        
        tbody.appendChild(tr);
        containerIndex++;
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
    }
</script>

<?php require_once 'app/Views/layouts/footer.php'; ?>