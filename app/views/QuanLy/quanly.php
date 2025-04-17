<?php
session_start();
require_once __DIR__ . "/../../controllers/QuanLyController.php";

$controller = new QuanLyController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$danhSachThanhToan = $controller->getPaymentList($status, $search, $page);
$totalEvents = $controller->countEvents($status, $search);
$limit = 10;
$totalPages = ceil($totalEvents / $limit);
$availableYears = $controller->getAvailableYears();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cap_nhat_tt'])) {
    $maSK = $_POST['ma_sk'];
    $trangThai = $_POST['trang_thai'];
    $phuongThuc = $_POST['phuong_thuc'];
    
    if ($controller->updatePaymentStatus($maSK, $trangThai, $phuongThuc)) {
        $thongBao = "Cập nhật trạng thái thanh toán thành công!";
        $danhSachThanhToan = $controller->getPaymentList($status, $search, $page);
    } else {
        $loiThongBao = "Có lỗi xảy ra khi cập nhật trạng thái thanh toán!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_statistics'])) {
    $type = $_POST['statistic_type'];
    $month = !empty($_POST['month']) ? (int)$_POST['month'] : null;
    $year = !empty($_POST['year']) ? (int)$_POST['year'] : null;
    $controller->exportStatistics($type, $month, $year);
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý thanh toán sự kiện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(90deg, #007bff, #00b4db);
            border-radius: 15px 15px 0 0;
            color: white;
            font-weight: bold;
        }
        .form-control, .form-select {
            border-radius: 8px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }
        .btn-primary, .btn-success {
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .btn-primary:hover, .btn-success:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 8px;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.9em;
        }
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-calendar-check me-2"></i>Quản lý Sự kiện</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quản lý sự kiện</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Thanh toán</a></li>
                </ul>
                <a href="/Quan_Ly_Su_Kien/app/views/auth/logout.php" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Quản lý thanh toán</h4>
                <div>
                    <button class="btn btn-light btn-sm me-2" id="refreshBtn"><i class="fas fa-sync me-1"></i>Làm mới</button>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal"><i class="fas fa-file-export me-1"></i>Xuất thống kê</button>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (isset($thongBao)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $thongBao; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($loiThongBao)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $loiThongBao; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="GET" class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo mã, tên sự kiện hoặc khách hàng...">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <select name="status" class="form-select w-auto d-inline" onchange="this.form.submit()">
                            <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                            <option value="paid" <?php echo $status == 'paid' ? 'selected' : ''; ?>>Đã thanh toán</option>
                            <option value="unpaid" <?php echo $status == 'unpaid' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Mã SK</th>
                                <th>Tên sự kiện</th>
                                <th>Khách hàng</th>
                                <th>Loại sự kiện</th>
                                <th>Người tham gia</th>
                                <th>Giá tiền</th>
                                <th>Trạng thái</th>
                                <th>Phương thức</th>
                                <th>Ngày thanh toán</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSachThanhToan as $tt): ?>
                                <tr>
                                    <td><?php echo $tt['MA_SK']; ?></td>
                                    <td><?php echo htmlspecialchars($tt['TEN_SK']); ?></td>
                                    <td><?php echo htmlspecialchars($tt['TEN_KH']); ?></td>
                                    <td><?php echo ucfirst($tt['LOAI_SK']); ?></td>
                                    <td><?php echo $tt['NGUOI_THAM_GIA']; ?></td>
                                    <td><?php echo $controller->formatCurrency($tt['GIA_TIEN']); ?></td>
                                    <td><span class="badge <?php echo $tt['TRANG_THAI'] == 'Đã thanh toán' ? 'bg-success' : 'bg-warning'; ?>"><?php echo $tt['TRANG_THAI'] ?? 'Chưa thanh toán'; ?></span></td>
                                    <td><?php echo $tt['PHUONG_THUC'] ?? 'Chưa có'; ?></td>
                                    <td><?php echo $tt['NGAY_THANH_TOAN'] ?? 'Chưa có'; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $tt['MA_SK']; ?>"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal<?php echo $tt['MA_SK']; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5>Cập nhật thanh toán</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="ma_sk" value="<?php echo $tt['MA_SK']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên sự kiện</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($tt['TEN_SK']); ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Trạng thái</label>
                                                        <select name="trang_thai" class="form-select">
                                                            <option value="1" <?php echo $tt['TRANG_THAI'] == 'Đã thanh toán' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                                            <option value="0" <?php echo $tt['TRANG_THAI'] != 'Đã thanh toán' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Phương thức</label>
                                                        <select name="phuong_thuc" class="form-select">
                                                            <option value="">-- Chọn --</option>
                                                            <option value="Tiền mặt" <?php echo $tt['PHUONG_THUC'] == 'Tiền mặt' ? 'selected' : ''; ?>>Tiền mặt</option>
                                                            <option value="Chuyển khoản" <?php echo $tt['PHUONG_THUC'] == 'Chuyển khoản' ? 'selected' : ''; ?>>Chuyển khoản</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" name="cap_nhat_tt" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>">Trước</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-file-export me-2"></i>Xuất thống kê</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" onsubmit="return validateExportForm()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Loại thống kê <span class="text-danger">*</span></label>
                            <select name="statistic_type" class="form-select" required>
                                <option value="" disabled selected>Chọn loại thống kê</option>
                                <option value="event_count">Số lượng sự kiện theo tháng, năm</option>
                                <option value="event_type">Số lượng sự kiện theo loại, tháng, năm</option>
                                <option value="revenue">Doanh thu theo tháng, năm</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tháng</label>
                            <select name="month" class="form-select">
                                <option value="">Tất cả tháng</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Năm</label>
                            <select name="year" class="form-select">
                                <option value="">Tất cả năm</option>
                                <?php foreach ($availableYears as $year): ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <p class="text-muted">File Excel (.xlsx) sẽ chứa dữ liệu thống kê theo tiêu chí đã chọn, với định dạng đẹp và tổng hợp số liệu.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="export_statistics" class="btn btn-success">Xuất Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('refreshBtn').addEventListener('click', function() {
            window.location.href = '?page=1&status=all&search=';
        });

        function validateExportForm() {
            const type = document.querySelector('select[name="statistic_type"]').value;
            if (!type) {
                alert('Vui lòng chọn loại thống kê.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
?>