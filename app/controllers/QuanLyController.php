<?php
require_once __DIR__ . '/../models/QuanLyModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class QuanLyController {
    private $model;
    
    public function __construct() {
        $this->model = new QuanLyModel();
    }
    
    public function getPaymentList($status = 'all', $search = '', $page = 1) {
        return $this->model->getAllEvents($status, $search, $page);
    }
    
    public function countEvents($status = 'all', $search = '') {
        return $this->model->countEvents($status, $search);
    }
    
    public function updatePaymentStatus($eventId, $status, $method = null) {
        return $this->model->updatePaymentStatus($eventId, $status, $method);
    }
    
    public function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }

    public function getAvailableYears() {
        return $this->model->getAvailableYears();
    }

    public function exportStatistics($type, $month = null, $year = null) {
        try {
            // Tạo spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Thiết lập font hỗ trợ tiếng Việt
            $sheet->getStyle('A1:Z999')->getFont()->setName('Arial')->setSize(11);

            // Tiêu đề file
            $sheet->setCellValue('A1', 'Thống kê sự kiện');
            $sheet->mergeCells('A1:D1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('A2', 'Ngày xuất: ' . date('d/m/Y H:i:s'));
            $sheet->mergeCells('A2:D2');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Xử lý từng loại thống kê
            if ($type === 'event_count') {
                // Thống kê số lượng sự kiện theo tháng, năm
                $data = $this->model->countEventsByMonthYear($month, $year);
                
                // Tiêu đề cột
                $sheet->setCellValue('A4', 'Năm');
                $sheet->setCellValue('B4', 'Tháng');
                $sheet->setCellValue('C4', 'Số lượng sự kiện');
                $sheet->getStyle('A4:C4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('007BFF');
                $sheet->getStyle('A4:C4')->getFont()->setBold(true)->getColor()->setARGB('FFFFFF');
                $sheet->getStyle('A4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:C4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Dữ liệu
                $row = 5;
                $totalEvents = 0;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item['nam']);
                    $sheet->setCellValue('B' . $row, $item['thang']);
                    $sheet->setCellValue('C' . $row, $item['so_luong']);
                    $sheet->getStyle('A' . $row . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $totalEvents += $item['so_luong'];
                    $row++;
                }

                // Dòng tổng hợp
                $sheet->setCellValue('A' . $row, 'Tổng');
                $sheet->mergeCells('A' . $row . ':B' . $row);
                $sheet->setCellValue('C' . $row, $totalEvents);
                $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E9ECEF');
                $sheet->getStyle('A' . $row . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Tự động điều chỉnh độ rộng cột
                foreach (range('A', 'C') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            } elseif ($type === 'event_type') {
                // Thống kê số lượng sự kiện theo loại, tháng, năm
                $data = $this->model->countEventsByTypeMonthYear($month, $year);
                
                // Tiêu đề cột
                $sheet->setCellValue('A4', 'Năm');
                $sheet->setCellValue('B4', 'Tháng');
                $sheet->setCellValue('C4', 'Loại sự kiện');
                $sheet->setCellValue('D4', 'Số lượng');
                $sheet->getStyle('A4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('007BFF');
                $sheet->getStyle('A4:D4')->getFont()->setBold(true)->getColor()->setARGB('FFFFFF');
                $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Dữ liệu
                $row = 5;
                $totalEvents = 0;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item['nam']);
                    $sheet->setCellValue('B' . $row, $item['thang']);
                    $sheet->setCellValue('C' . $row, ucfirst($item['LOAI_SK']));
                    $sheet->setCellValue('D' . $row, $item['so_luong']);
                    $sheet->getStyle('A' . $row . ':D' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $totalEvents += $item['so_luong'];
                    $row++;
                }

                // Dòng tổng hợp
                $sheet->setCellValue('A' . $row, 'Tổng');
                $sheet->mergeCells('A' . $row . ':C' . $row);
                $sheet->setCellValue('D' . $row, $totalEvents);
                $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':D' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E9ECEF');
                $sheet->getStyle('A' . $row . ':D' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Tự động điều chỉnh độ rộng cột
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            } elseif ($type === 'revenue') {
                // Thống kê doanh thu theo tháng, năm
                $data = $this->model->revenueByMonthYear($month, $year);
                
                // Tiêu đề cột
                $sheet->setCellValue('A4', 'Năm');
                $sheet->setCellValue('B4', 'Tháng');
                $sheet->setCellValue('C4', 'Doanh thu (VNĐ)');
                $sheet->getStyle('A4:C4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('007BFF');
                $sheet->getStyle('A4:C4')->getFont()->setBold(true)->getColor()->setARGB('FFFFFF');
                $sheet->getStyle('A4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:C4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Dữ liệu
                $row = 5;
                $totalRevenue = 0;
                foreach ($data as $item) {
                    $sheet->setCellValue('A' . $row, $item['nam']);
                    $sheet->setCellValue('B' . $row, $item['thang']);
                    $sheet->setCellValue('C' . $row, $item['doanh_thu']);
                    $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('A' . $row . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $totalRevenue += $item['doanh_thu'];
                    $row++;
                }

                // Dòng tổng hợp
                $sheet->setCellValue('A' . $row, 'Tổng');
                $sheet->mergeCells('A' . $row . ':B' . $row);
                $sheet->setCellValue('C' . $row, $totalRevenue);
                $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E9ECEF');
                $sheet->getStyle('A' . $row . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Tự động điều chỉnh độ rộng cột
                foreach (range('A', 'C') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }

            // Xuất file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="thong_ke_' . $type . '_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            error_log('Lỗi xuất thống kê Excel: ' . $e->getMessage());
            header('Content-Type: text/html; charset=utf-8');
            echo '<h1>Lỗi xuất thống kê</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
            exit;
        }
    }
}
?>