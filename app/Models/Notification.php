<?php
namespace App\Models;

use App\Models\Traits\HasTimestamps;
use PDO;
use Exception;

abstract class Notification {
    use HasTimestamps;

    protected PDO $pdo;
    protected string $title;
    protected string $body;
    protected string $level; // info|warning|urgent
    protected bool $isRead = false;

    public function __construct(string $title, string $body, string $level = 'info') {
        $this->title = $title;
        $this->body = $body;
        $this->level = $level;
        $this->initTimestamps();
    }

   public function createNotification($userId, $title, $message, $relatedOrderId = null) {
        try {
            $sql = "INSERT INTO notifications (id, title, message, is_read, created_at) 
                    VALUES (?, ?, ?, ?, 0, NOW())";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$userId, $title, $message, $relatedOrderId]);
        } catch (Exception $e) {
            error_log("Lỗi tạo thông báo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách thông báo chưa đọc
     */
    public function getUnreadNotifications($userId, $limit = 10) {
        $sql = "SELECT * FROM notifications 
                WHERE id = ? AND is_read = 0 
                ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        // Bind param cho LIMIT bắt buộc phải ép kiểu INT trong PDO
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm tổng số thông báo chưa đọc
     */
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function toArray(): array {
        return [
            'title'      => $this->title,
            'body'       => $this->body,
            'level'      => $this->level,
            'is_read'    => $this->isRead,
            'created_at' => $this->getCreatedAt()
        ];
    }
}