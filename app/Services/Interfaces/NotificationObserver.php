<?php
namespace App\Services\Interfaces;

interface NotificationObserver {
    /**
     * Hàm được gọi khi có sự kiện thay đổi trạng thái đơn hàng
     * @param array $orderData Dữ liệu đơn hàng vừa được cập nhật
     * @param string $action Hành động (approve, reject, complete)
     * @param string $reason Lý do (nếu có, dùng cho reject)
     */
    public function onOrderStatusChanged(array $orderData, string $action, string $reason = '');
}

