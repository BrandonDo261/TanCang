<div class="dash-card p-3 mb-4">
    
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <?php $stt = $_GET['status'] ?? 'all'; ?>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=all&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= ($stt == 'all' && empty($_GET['search_text'])) ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Tất cả</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=cho_duyet&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'cho_duyet' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Chờ duyệt (<?= $counts['Chờ duyệt'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=tu_choi&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'tu_choi' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Duyệt từ chối (<?= $counts['Duyệt từ chối'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=dong_y&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'dong_y' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Duyệt đồng ý (<?= $counts['Duyệt đồng ý'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=da_thanh_toan&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'da_thanh_toan' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Đã thanh toán (<?= $counts['Đã thanh toán'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=dang_lam_hang&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'dang_lam_hang' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Đang làm hàng (<?= $counts['Đang làm hàng'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=hoan_thanh&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'hoan_thanh' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Hoàn thành (<?= $counts['Hoàn thành'] ?? 0 ?>)</a>

    <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 ms-auto d-flex align-items-center" style="height: 31px;" data-bs-toggle="modal" data-bs-target="#rejectOldPriceModal">
       <i class="fas fa-times-circle me-1"></i> Từ chối đơn giá cũ
    </button>
</div>