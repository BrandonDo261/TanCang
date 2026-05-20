<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrderExport extends BaseExport {
    private $order;
    private $details;

    public function __construct($order, $details) {
        parent::__construct(); 
        $this->order = $order;
        $this->details = $details;
    }

    protected function buildSheet() {
        $this->sheet->setTitle('Phiếu Đơn Hàng');

        $this->sheet->setCellValue('A1', 'PHIẾU TIẾP NHẬN ĐƠN HÀNG - TÂN CẢNG');
        $this->sheet->mergeCells('A1:B1');
        $this->sheet->setCellValue('A3', 'Mã đơn hàng');
        $this->sheet->setCellValue('B3', $this->order['order_code']);
        $this->sheet->setCellValue('A4', 'Người tạo');
        $this->sheet->setCellValue('B4', $this->order['creator_name']);
        $this->sheet->setCellValue('A5', 'Ngày gửi');
        $this->sheet->setCellValue('B5', date('d/m/Y H:i', strtotime($this->order['created_at'])));
        $this->sheet->setCellValue('A6', 'Phương án');
        $this->sheet->setCellValue('B6', $this->order['action_type']);
        $this->sheet->setCellValue('A7', 'Depot');
        $this->sheet->setCellValue('B7', $this->order['depot_name']);
        $this->sheet->setCellValue('A8', 'Hãng tàu');
        $this->sheet->setCellValue('B8', $this->order['shipping_line']);
        $this->sheet->setCellValue('A9', 'Số BL/DO/BKG');
        $this->sheet->setCellValue('B9', $this->order['bl_do_bkg'] ? $this->order['bl_do_bkg'] : 'N/A');
        
        $this->sheet->setCellValue('A11', 'CHI TIẾT CONTAINER');
        $this->sheet->mergeCells('A11:B11');

        $currentRow = 12;
        if (empty($this->details)) {
            $this->sheet->setCellValue('A' . $currentRow, 'Loại Container');
            $this->sheet->setCellValue('B' . $currentRow, '0 cont');
            $currentRow++;
        } else {
            foreach ($this->details as $d) {
                $this->sheet->setCellValue('A' . $currentRow, $d['container_type']);
                $this->sheet->setCellValue('B' . $currentRow, $d['quantity'] . ' cont');
                $currentRow++;
            }
        }

        $this->sheet->setCellValue('A' . $currentRow, 'Ghi chú');
        $this->sheet->setCellValue('B' . $currentRow, $this->order['note'] ? $this->order['note'] : 'Không có');
        $currentRow++;

        $this->sheet->setCellValue('A' . $currentRow, 'Trạng thái');
        $this->sheet->setCellValue('B' . $currentRow, mb_strtoupper($this->order['status'], 'UTF-8'));
        $statusRow = $currentRow;

        // Định dạng Style
        $this->sheet->getStyle('A1:B' . $currentRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $this->sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF3275B2'); 
        $this->sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $this->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $this->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->sheet->getStyle('A11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFEAECEF');
        $this->sheet->getStyle('A11')->getFont()->setBold(true);
        $this->sheet->getStyle('A11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->sheet->getStyle('B' . $statusRow)->getFont()->getColor()->setARGB('FFD32F2F');
        $this->sheet->getStyle('B' . $statusRow)->getFont()->setBold(true);

        $styleArray = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ];
        $this->sheet->getStyle('A1:B' . $currentRow)->applyFromArray($styleArray);
        $this->sheet->getStyle('A3:A' . $currentRow)->getFont()->setBold(true);
        $this->sheet->getColumnDimension('A')->setWidth(20);
        $this->sheet->getColumnDimension('B')->setWidth(40);
        
        for ($i = 1; $i <= $currentRow; $i++) {
            $this->sheet->getRowDimension($i)->setRowHeight($i == 1 ? 35 : 22);
        }
    }
}