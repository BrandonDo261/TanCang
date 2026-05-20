<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class BaseExport {
    protected $spreadsheet;
    protected $sheet;

    public function __construct() {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    // Bắt buộc các Class con (Báo cáo, Đơn hàng) phải tự thiết kế giao diện bên trong hàm này
    abstract protected function buildSheet();

    // Hàm dùng chung để tải file về máy người dùng an toàn
    public function download($filename) {
        $this->buildSheet();

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit();
    }
}