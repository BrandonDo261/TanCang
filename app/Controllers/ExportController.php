<?php
namespace App\Controllers;

use App\Exports\OrderExport;
use PDO;

class ExportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function order() {
        if (!isset($_GET['id'])) die("Thiếu mã đơn hàng.");
        $id = $_GET['id'];

        // 1. Truy vấn Database (Sau này có thể tách ra OrderModel cho sạch đẹp hơn nữa)
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) die("Không tìm thấy đơn hàng!");

        $stmtDetails = $this->pdo->prepare("
            SELECT p.container_type, d.quantity 
            FROM logis_order_details d
            JOIN logis_pricing p ON d.pricing_id = p.id
            WHERE d.order_id = ?
        ");
        $stmtDetails->execute([$id]);
        $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        // 2. Khởi tạo đối tượng Export và ra lệnh Download
        $filename = "Phieu_Don_Hang_" . $order['order_code'] . ".xlsx";
        
        $export = new OrderExport($order, $details);
        $export->download($filename);
    }
}