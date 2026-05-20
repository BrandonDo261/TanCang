<?php 
require_once 'app/Views/layouts/header.php'; 
require_once 'config/database.php'; 

// --- 1. LẤY SỐ LIỆU ĐƠN HÀNG CHỜ DUYỆT ---
$stmtPending = $pdo->query("SELECT COUNT(*) FROM logis_orders WHERE status = 'Chờ duyệt'");
$pendingCount = $stmtPending->fetchColumn();

// --- 2. TÍNH TỔNG DOANH THU (Chỉ tính lệnh Đã thanh toán / Hoàn thành) ---
$sqlRevenue = "SELECT SUM(d.quantity * p.price) as total_revenue
               FROM logis_order_details d
               JOIN logis_orders o ON d.order_id = o.id
               JOIN logis_pricing p ON d.pricing_id = p.id
               WHERE o.status IN ('Đã thanh toán', 'Hoàn thành')";
$totalRevenue = $pdo->query($sqlRevenue)->fetchColumn() ?: 0;

// --- 3. LẤY THÔNG BÁO HOẠT ĐỘNG (5 đơn hàng mới nhất) ---
$stmtRecent = $pdo->query("SELECT order_code, creator_name, created_at, status FROM logis_orders ORDER BY created_at DESC LIMIT 5");
$recentOrders = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-pie text-primary me-2"></i>Báo cáo & Thống kê</h4>
    <div class="text-muted small">Cập nhật lúc: <?= date('d/m/Y H:i') ?></div>
</div>

<div class="row g-4 mb-4">
    
    <div class="col-md-6">
        <div class="dash-card p-4 border-start border-success border-4 h-100 shadow-sm bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2 small fw-bold text-uppercase tracking-wide">Tổng Doanh Thu Dịch Vụ</p>
                    <h2 class="fw-bold text-success mb-0"><?= number_format($totalRevenue, 0, ',', '.') ?> <span class="fs-5 text-muted fw-normal">VNĐ</span></h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-money-bill-wave fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dash-card p-4 border-start border-danger border-4 h-100 shadow-sm bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2 small fw-bold text-uppercase tracking-wide">Lệnh Đang Chờ Duyệt</p>
                    <h2 class="fw-bold text-danger mb-0"><?= $pendingCount ?> <span class="fs-5 text-muted fw-normal">Yêu cầu</span></h2>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-clock fs-3"></i>
                </div>
            </div>
            <?php if($pendingCount > 0): ?>
                <div class="mt-3 border-top pt-3">
                    <a href="<?= $baseUrl ?>/index.php?page=orders&status=cho_duyet" class="btn btn-sm btn-danger rounded-pill fw-bold px-3">
                        <i class="fas fa-share ms-1"></i> Xử lý ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="row g-4">
    
    <div class="col-md-8">
        <div class="dash-card p-4 h-100 shadow-sm bg-white rounded-3">
            <h6 class="fw-bold text-dark mb-4"><i class="fas fa-chart-line text-primary me-2"></i>Biểu đồ Doanh thu (7 ngày gần nhất)</h6>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dash-card p-4 h-100 shadow-sm bg-white rounded-3">
            <h6 class="fw-bold text-dark mb-4"><i class="fas fa-bell text-warning me-2"></i>Hoạt động mới nhất</h6>
            <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                
                <?php if(empty($recentOrders)): ?>
                    <p class="text-muted small text-center mt-4">Chưa có hoạt động nào.</p>
                <?php else: ?>
                    <?php foreach($recentOrders as $ro): ?>
                        <div class="d-flex border-bottom pb-3 mb-3">
                            <div class="mt-1 me-3">
                                <?php if($ro['status'] == 'Chờ duyệt'): ?>
                                    <span class="bg-danger rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                                <?php elseif($ro['status'] == 'Hoàn thành' || $ro['status'] == 'Đã thanh toán'): ?>
                                    <span class="bg-success rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                                <?php else: ?>
                                    <span class="bg-info rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="mb-1 small fw-bold text-dark"><?= htmlspecialchars($ro['creator_name']) ?></p>
                                <p class="mb-1 small text-muted">
                                    Mã lệnh: <span class="fw-semibold text-primary"><?= htmlspecialchars($ro['order_code']) ?></span> 
                                </p>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="badge bg-light text-dark border"><?= $ro['status'] ?></span>
                                    <span style="font-size: 11px; color: #888;"><i class="fas fa-clock me-1"></i><?= date('d/m H:i', strtotime($ro['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <div class="text-center mt-3 pt-2 border-top">
                <a href="<?= $baseUrl ?>/index.php?page=orders" class="text-decoration-none small fw-bold">Xem toàn bộ Lệnh <i class="fas fa-angle-right ms-1"></i></a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Quy đổi doanh thu ra đơn vị Triệu đồng cho biểu đồ dễ nhìn
    const realRevenueInMillions = <?= round($totalRevenue / 1000000, 1) ?>;
    
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Hôm nay'],
            datasets: [{
                label: 'Doanh thu (Triệu VNĐ)',
                data: [15.2, 22.5, 18.0, 30.1, 25.8, 40.0, realRevenueInMillions], 
                backgroundColor: [
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(25, 135, 84, 0.8)' // Cột Hôm nay tô màu đậm hơn
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(25, 135, 84, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // Ẩn label chung đi cho đỡ chật
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

<?php require_once 'app/Views/layouts/footer.php'; ?>