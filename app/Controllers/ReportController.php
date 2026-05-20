<?php
// Đường dẫn: app/Controllers/ReportController.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportController {
    
    // Hàm hiển thị trang chọn ngày
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require 'app/Views/reports/index.php';
    }

    // Hàm thực hiện xử lý xuất file Excel
    public function exportExcel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=reports");
            exit;
        }

        global $pdo;

        $from_date = $_POST['from_date'] . ' 00:00:00';
        $to_date   = $_POST['to_date'] . ' 23:59:59';

        // 1. Lấy dữ liệu lệnh cảng & Tính toán doanh thu từ bảng details và pricing
        $sql = "SELECT 
                    o.order_code, o.creator_name, o.action_type, o.depot_name, 
                    o.shipping_line, o.bl_do_bkg, o.status, o.created_at,
                    COALESCE(SUM(d.quantity * p.price), 0) AS doanh_thu
                FROM logis_orders o
                LEFT JOIN logis_order_details d ON o.id = d.order_id
                LEFT JOIN logis_pricing p ON d.pricing_id = p.id
                WHERE o.created_at BETWEEN ? AND ?
                GROUP BY o.id, o.order_code, o.creator_name, o.action_type, o.depot_name, o.shipping_line, o.bl_do_bkg, o.status, o.created_at
                ORDER BY o.created_at DESC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$from_date, $to_date]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Khởi tạo đối tượng Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Bao Cao Epot');

        // 3. Thiết kế Tiêu đề chính trong file Excel
        $sheet->setCellValue('A1', 'BÁO CÁO QUẢN LÝ LỆNH CẢNG E-POT');
        $sheet->mergeCells('A1:J1'); // Mở rộng merge cell thêm 1 cột
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Thời gian lệnh: Từ ' . date('d/m/Y', strtotime($_POST['from_date'])) . ' đến ' . date('d/m/Y', strtotime($_POST['to_date'])));
        $sheet->mergeCells('A2:J2'); // Mở rộng merge cell
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // 4. Tạo Header cho bảng dữ liệu (Bổ sung cột Doanh Thu)
        $headers = [
            'A4' => 'STT',
            'B4' => 'Mã Đơn Hàng',
            'C4' => 'Người Tạo Lệnh',
            'D4' => 'Loại Tác Vụ',
            'E4' => 'Tên Depot',
            'F4' => 'Hãng Tàu',
            'G4' => 'Số B/L - D/O - BKG',
            'H4' => 'Trạng Thái',
            'I4' => 'Doanh Thu (VNĐ)', // Cột mới thêm
            'J4' => 'Ngày Tạo'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78']
            ]
        ];
        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle); // Áp dụng style đến cột J
        $sheet->getRowDimension(4)->setRowHeight(28);

        // 5. Đổ dữ liệu vòng lặp
        $rowNumber = 5;
        $stt = 1;
        
        $statusMap = [
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet'  => 'Đã duyệt',
            'tu_choi'   => 'Từ chối'
        ];

        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $rowNumber, $stt++);
            $sheet->setCellValue('B' . $rowNumber, $order['order_code']);
            $sheet->setCellValue('C' . $rowNumber, $order['creator_name']);
            $sheet->setCellValue('D' . $rowNumber, $order['action_type']);
            $sheet->setCellValue('E' . $rowNumber, $order['depot_name']);
            $sheet->setCellValue('F' . $rowNumber, $order['shipping_line']);
            $sheet->setCellValue('G' . $rowNumber, $order['bl_do_bkg']);
            
            $statusText = $statusMap[$order['status']] ?? $order['status'];
            $sheet->setCellValue('H' . $rowNumber, $statusText);
            
            // Đổ dữ liệu Doanh thu và set format tiền tệ
            $sheet->setCellValue('I' . $rowNumber, $order['doanh_thu']);
            $sheet->getStyle('I' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            
            $sheet->setCellValue('J' . $rowNumber, date('d/m/Y H:i', strtotime($order['created_at'])));

            // Căn giữa
            $sheet->getStyle('A'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $rowNumber++;
        }

        // 6. Tạo đường viền (Borders)
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];
        $sheet->getStyle('A4:J' . ($rowNumber - 1))->applyFromArray($borderStyle);

        // 7. Tự động co giãn cột
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 8. Cấu hình Header và tải file
        $fileName = 'Bao_cao_Epot_DoanhThu_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}