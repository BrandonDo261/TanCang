<?php
namespace App\Services;

use App\Services\Interfaces\NotificationObserver;
use App\Models\SystemNotification;
use PDO;

class InAppNotifier implements NotificationObserver {
    private $notificationModel;
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        // SystemNotification constructor expects a string as first arg and PDO as second; provide empty string for first
        $this->notificationModel = new SystemNotification('', $this->pdo);
    }

    public function onOrderStatusChanged(array $orderData, string $action, string $reason = '') {
        // 1. Tìm ID của nhân viên tạo đơn hàng (để biết gửi thông báo cho ai)
        $stmt = $this->pdo->prepare("SELECT id FROM logis_users WHERE username = ?");
        $stmt->execute([$orderData['creator_name']]);
        $creatorId = $stmt->fetchColumn();

        if (!$creatorId) {
            return; // Không tìm thấy người nhận
        }

        // 2. Dịch hành động thành thông điệp
        $title = "Cập nhật đơn hàng " . $orderData['order_code'];
        $message = "";

        switch ($action) {
            case 'approve':
                $message = "Đơn hàng của bạn đã được duyệt đồng ý bởi " . $orderData['approver_name'] . ".";
                break;
            case 'reject':
                $message = "Đơn hàng của bạn bị TỪ CHỐI bởi " . $orderData['approver_name'] . ". Lý do: " . $reason;
                break;
            case 'complete':
                $message = "Đơn hàng " . $orderData['order_code'] . " đã được xác nhận hoàn tất.";
                break;
        }

        // 3. Ghi vào cơ sở dữ liệu
        $this->notificationModel->createNotification($creatorId, $title, $message, $orderData['id']);
    }
}