<?php
// Đường dẫn: app/Models/ReportModel.php

class ReportModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy tổng quan các con số
    public function getGeneralStats() {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'cho_duyet' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN status = 'da_duyet' THEN 1 ELSE 0 END) as approved_orders,
                    SUM(CASE WHEN status = 'tu_choi' THEN 1 ELSE 0 END) as rejected_orders
                FROM logis_orders";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thống kê số lượng theo từng trạng thái (cho biểu đồ tròn)
    public function getStatsByStatus() {
        $sql = "SELECT status, COUNT(*) as count FROM logis_orders GROUP BY status";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy số lượng đơn hàng 7 ngày gần nhất (cho biểu đồ cột)
    public function getOrdersLast7Days() {
        $sql = "SELECT DATE(created_at) as order_date, COUNT(*) as total 
                FROM logis_orders 
                WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY 
                GROUP BY DATE(created_at) 
                ORDER BY order_date ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}