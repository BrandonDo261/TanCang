<div class="d-flex justify-content-between align-items-center mt-3">
        <?php 
            // Tạo query string để giữ lại các bộ lọc hiện tại
            $query_params = $_GET; 
            unset($query_params['p']); // Xóa trang cũ để gán trang mới
            $base_query = http_build_query($query_params);
        ?>
        
        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted">Hiển thị:</span>
            <select class="form-select form-select-sm w-auto" onchange="location.href='index.php?<?= $base_query ?>&limit=' + this.value + '&p=1'">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 dòng</option>
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 dòng</option>
            </select>
            <span class="small text-muted">/ Tổng <?= $total_records ?> đơn</span>
        </div>

        <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $p <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?<?= $base_query ?>&p=1">&laquo;</a>
            </li>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $p == $i ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?<?= $base_query ?>&p=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $p >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?<?= $base_query ?>&p=<?= $total_pages ?>">&raquo;</a>
            </li>
        </ul>
    </div>