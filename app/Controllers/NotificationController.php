<?php
namespace App\Controllers;

use App\Services\NotificationManager;
use App\Services\InAppNotifier;

class NotificationController {
    private NotificationManager $manager;
    private InAppNotifier $notifier;

    public function __construct(NotificationManager $manager, InAppNotifier $notifier) {
        $this->manager = $manager;
        $this->notifier = $notifier;
    }

    public function triggerEventAction(string $event, string $title) {
        $this->manager->notify($event, ['name' => $title]);
        return "Sự kiện $event đã được kích hoạt!";
    }

    public function fetchNotificationsAPI() {
        return json_encode([
            'unread_count'  => $this->notifier->countUnread(),
            'notifications' => $this->notifier->getAllNotifications()
        ]);
    }
}
