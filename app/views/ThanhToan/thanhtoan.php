<?php
session_start();
require_once __DIR__ . "/../../controllers/ThanhToanController.php";

$controller = new ThanhToanController();
$thongTinTT = $controller->thongtinTT();

if (!$thongTinTT) {
    header("Location: user.php");
    exit;
}

// Format thông tin
$tongTien = $controller->formatCurrency($thongTinTT['gia_tien']);
$tienCocCanThanhToan = $controller->formatCurrency($thongTinTT['gia_tien'] * 0.5);
$ngayBatDau = $controller->formatDateTime($thongTinTT['NGAY_BD']);
$ngayKetThuc = $controller->formatDateTime($thongTinTT['NGAY_KT']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán sự kiện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(90deg, #007bff, #00b4db);
            border-radius: 15px 15px 0 0;
            color: white;
        }
        .alert-info {
            border-radius: 8px;
            background-color: #e7f3fe;
            border-color: #b6d4fe;
        }
        .progress {
            height: 25px;
            border-radius: 8px;
        }
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
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
                    <li class="nav-item"><a class="nav-link" href="/Quan_Ly_Su_Kien/app/views/auth/logout.php">Đăng ký sự kiện</a></li>
                    <li class="nav-item"><a class="nav-link" href="user.php#viewEvents">Sự kiện đã đăng ký</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Thanh toán</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3"><i class="fas fa-user-circle me-1"></i>Xin chào, <?php echo htmlspecialchars($thongTinTT['TEN_KH']); ?></span>
                    <a href="#" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thanh toán sự kiện</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading">Thông tin sự kiện</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Người đăng ký:</strong> <?php echo htmlspecialchars($thongTinTT['TEN_KH']); ?></p>
                            <p><strong>Số điện thoại:</strong> <?php echo $thongTinTT['SDT']; ?></p>
                            <p><strong>Địa điểm:</strong> <?php echo htmlspecialchars($thongTinTT['NOI_TO_CHUC']); ?></p>
                            <p><strong>Mã sự kiện:</strong> <?php echo $thongTinTT['MA_SK']; ?></p>
                            <p><strong>Tên sự kiện:</strong> <?php echo htmlspecialchars($thongTinTT['TEN_SK']); ?></p>
                            <p><strong>Loại sự kiện:</strong> <?php echo ucfirst($thongTinTT['LOAI_SK']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Thời gian bắt đầu:</strong> <?php echo $ngayBatDau; ?></p>
                            <p><strong>Thời gian kết thúc:</strong> <?php echo $ngayKetThuc; ?></p>
                            <p><strong>Số người tham gia:</strong> <?php echo $thongTinTT['NGUOI_THAM_GIA']; ?> người</p>
                            <p><strong>Tổng số tiền:</strong> <span class="text-danger fw-bold"><?php echo $tongTien; ?></span></p>
                            <p><strong>Cần thanh toán (đặt cọc 50%):</strong> <span class="text-danger fw-bold"><?php echo $tienCocCanThanhToan; ?></span></p>
                            <p><strong>Trạng thái:</strong> <span class="badge bg-warning">Chờ thanh toán</span></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="text-center mb-3">Tiến độ thanh toán</h5>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>

                <h4 class="mb-4"><i class="fas fa-wallet me-2"></i>Chọn phương thức thanh toán</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cashMethod" checked>
                                    <label class="form-check-label" for="cashMethod"><i class="fas fa-money-bill-wave me-2"></i>Tiền mặt</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5><i class="fas fa-map-marker-alt me-2"></i>Thanh toán trực tiếp</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-building me-2"></i>Số 123 Đường ABC, Quận 1, TP.HCM</li>
                                    <li><i class="fas fa-clock me-2"></i>Thứ 2 - Thứ 6: 8:00 - 17:00</li>
                                    <li><i class="fas fa-phone me-2"></i>028 1234 5678</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bankMethod">
                                    <label class="form-check-label" for="bankMethod"><i class="fas fa-university me-2"></i>Chuyển khoản</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-info-circle me-2"></i>Thông tin tài khoản</h5>
                                        <ul class="list-unstyled">
                                            <li>Ngân hàng: Vietcombank</li>
                                            <li>Số TK: 123456789</li>
                                            <li>Chủ TK: Công Ty XYZ</li>
                                            <li>Nội dung: SK<?php echo $thongTinTT['MA_SK']; ?>_<?php echo $thongTinTT['TEN_KH']; ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <h5><i class="fas fa-qrcode me-2"></i>QR Code</h5>
                                        <img src="/api/placeholder/150/150" alt="QR Code Thanh Toán" class="img-fluid mb-2" />
                                        <p class="small text-muted">Quét mã để thanh toán</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5><i class="fas fa-info-circle me-2"></i>Thông tin thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Vui lòng đặt cọc <strong>50%</strong> tổng chi phí để xác nhận đăng ký sự kiện.</li>
                            <li>Số tiền còn lại sẽ được thanh toán sau khi kết thúc sự kiện.</li>
                            <li>Trong trường hợp hủy sự kiện, số tiền cọc sẽ không được hoàn lại.</li>
                            <li>Vui lòng liên hệ <strong>028 1234 5678</strong> nếu cần hỗ trợ thêm.</li>
                        </ul>
                        <div class="form-floating mt-3">
                            <textarea class="form-control" id="paymentNote" style="height: 100px" placeholder="Ghi chú thanh toán"></textarea>
                            <label for="paymentNote">Ghi chú thanh toán (nếu có)</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button class="btn btn-secondary" id="cancelPayment"><i class="fas fa-times me-2"></i>Hủy bỏ</button>
                    <button class="btn btn-success" id="confirmPayment" data-bs-toggle="modal" data-bs-target="#successModal">
                        <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Thanh toán thành công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle text-success mb-4" style="font-size: 60px"></i>
                    <h4>Đã thanh toán thành công!</h4>
                    <p>Cảm ơn <strong><?php echo htmlspecialchars($thongTinTT['TEN_KH']); ?></strong> đã đăng ký và thanh toán!</p>
                    <p class="text-muted">Mã giao dịch: #PAY<?php echo mt_rand(100000, 999999); ?></p>
                    <p>Thông tin xác nhận đã được gửi đến email của bạn.</p>
                </div>
                <div class="modal-footer">
                    <a href="/Quan_Ly_Su_Kien/app/views/NguoiDung/user.php" class="btn btn-primary"><i class="fas fa-home me-2"></i>Về trang chủ</a>
                    <button class="btn btn-success" data-bs-dismiss="modal"><i class="fas fa-print me-2"></i>In biên lai</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cancelPayment').addEventListener('click', function() {
                if (confirm('Bạn có chắc muốn hủy thanh toán này?')) {
                    window.location.href = 'user.php';
                }
            });

            document.getElementById('confirmPayment').addEventListener('click', function() {
                document.querySelector('.progress-bar').style.width = '50%';
                document.querySelector('.progress-bar').innerText = '50%';
            });
        });
    </script>
</body>
</html>
?>