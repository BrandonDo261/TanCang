<?php
namespace App\Services;

use App\Services\Interfaces\NotificationObserver;

class NotificationManager {
    /** @var NotificationObserver[] */
    private $observers = [];

    /**
     * Đăng ký một đơn vị nhận thông báo (Observer)
     */
    public function attach(NotificationObserver $observer) {
        $this->observers[] = $observer;
    }

    /**
     * Kích hoạt thông báo đến tất cả các đơn vị đã đăng ký
     */
    public function notifyOrderStatusChanged(array $orderData, string $action, string $reason = '') {
        foreach ($this->observers as $observer) {
            $observer->onOrderStatusChanged($orderData, $action, $reason);
        }
    }
}