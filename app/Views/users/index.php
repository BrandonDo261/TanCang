<?php require_once 'app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= $baseUrl ?>/public/css/pages/users.css">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Quản trị hệ thống tài khoản</h4>
        <p class="text-muted small mb-0 mt-1">Cấp quyền và giám sát hoạt động của nhân viên & khách hàng</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus-circle me-1"></i> Tạo tài khoản mới
    </button>
</div>

<?php include 'components/stats_cards.php'; ?>

<?php include 'components/user_table.php'; ?>

<?php include 'components/add_modal.php'; ?>
<?php include 'components/edit_modal.php'; ?>
<?php include 'components/reset_pass_modal.php'; ?>

<?php if(isset($_GET['msg'])): ?>
    <script>window.__user_msg = '<?= $_GET['msg'] ?>';</script>
<?php endif; ?>

<script src="<?= $baseUrl ?>/public/js/pages/users.js"></script>

<?php require_once 'app/Views/layouts/footer.php'; ?>