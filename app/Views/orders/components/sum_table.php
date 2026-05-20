<div class="row justify-content-center mb-4"> 
    <div class="col-12">
        <div class="dash-card p-3">
            <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-chart-pie text-success me-2"></i>Tổng hợp sản lượng trang hiện tại</h6>
            
            <div class="table-responsive table-scrollable">
                <table class="table table-bordered table-sm text-center align-middle mb-0 text-nowrap" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start" style="min-width: 130px;">Loại tác vụ</th>
                            
                            <?php foreach ($containerTypes as $cType): ?>
                                <th><?= htmlspecialchars($cType) ?></th>
                            <?php endforeach; ?>
                            
                            <th class="bg-warning bg-opacity-25 fw-bold">Tổng cộng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start fw-bold text-info"><i class="fas fa-arrow-down me-1"></i>Hạ rỗng</td>
                            
                            <?php foreach ($containerTypes as $cType): ?>
                                <td><?= $summary['ha_rong'][$cType] > 0 ? '<span class="fw-bold text-dark">'.$summary['ha_rong'][$cType].'</span>' : '<span class="text-muted opacity-50">-</span>' ?></td>
                            <?php endforeach; ?>
                            
                            <td class="bg-warning bg-opacity-10 fw-bold fs-6 text-danger"><?= $summary['ha_rong']['tong'] ?></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bold text-warning"><i class="fas fa-arrow-up me-1"></i>Cấp rỗng</td>
                            
                            <?php foreach ($containerTypes as $cType): ?>
                                <td><?= $summary['cap_rong'][$cType] > 0 ? '<span class="fw-bold text-dark">'.$summary['cap_rong'][$cType].'</span>' : '<span class="text-muted opacity-50">-</span>' ?></td>
                            <?php endforeach; ?>
                            
                            <td class="bg-warning bg-opacity-10 fw-bold fs-6 text-danger"><?= $summary['cap_rong']['tong'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>