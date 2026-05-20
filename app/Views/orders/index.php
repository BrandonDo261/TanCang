<?php require_once 'app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/pages/orders.css">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-check text-primary me-2"></i>Tiếp nhận đơn hàng</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=home"><i class="fas fa-home text-primary"></i></a></li>
            <li class="breadcrumb-item active">Tiếp nhận đơn</li>
        </ol>
    </nav>
</div>

<?php include 'components/filter_form.php'; ?>

<div class="dash-card p-3 mb-4">
    <?php include 'components/status_tabs.php'; ?>

    <?php include 'components/main_table.php'; ?>

    <?php include 'components/pagination.php'; ?>
</div>

<?php include 'components/sum_table.php'; ?>

<?php include 'components/reject_modal.php'; ?>

<script src="<?= $baseUrl ?>/public/js/pages/orders.js"></script>

<?php require_once 'app/Views/layouts/footer.php'; ?>