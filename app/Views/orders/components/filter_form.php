<div class="dash-card p-3 mb-4">
    <form action="index.php" method="GET" class="row g-2 align-items-end">
        
        <input type="hidden" name="page" value="orders">

        <input type="hidden" name="limit" value="<?= $limit ?>">
        
        <?php if(isset($_GET['status'])): ?>
            <input type="hidden" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
        <?php endif; ?>

        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Từ ngày: </label>
            <input type="date" name="from_date" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Đến ngày: </label>
            <input type="date" name="to_date" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
        </div>
        
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Tìm kiếm nhanh</label>
            <input type="text" name="search_text" class="form-control form-control-sm" placeholder="Mã đơn, Tên người tạo, số BL..." value="<?= htmlspecialchars($_GET['search_text'] ?? '') ?>">
        </div>
        
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Tìm kiếm theo:</label>
            <select name="search_type" class="form-select form-select-sm">
                <option value="all" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                <option value="order_code" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'order_code') ? 'selected' : '' ?>>Mã đơn</option>
                <option value="creator_name" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'creator_name') ? 'selected' : '' ?>>Người tạo</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
        </div>
    </form>
</div>