<?php
session_start();
require_once __DIR__ . "/../../controllers/NguoiDungController.php";

$controller = new NguoiDungController();
$thongBao = '';
$loiThongBao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dang_ky_su_kien'])) {
    $result = $controller->DKSuKien();
    if ($result['success']) {
        $thongBao = $result['message'];
        // Chuyển hướng đến thanhtoan.php sau 2 giây
        header("Refresh: 2; url=/Quan_Ly_Su_Kien/app/views/ThanhToan/thanhtoan.php");
    } else {
        $loiThongBao = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký sự kiện</title>
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
        .btn-primary {
            background: #007bff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
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
                    <li class="nav-item"><a class="nav-link active" href="#">Đăng ký sự kiện</a></li>
                    <li class="nav-item"><a class="nav-link" href="#viewEvents">Sự kiện đã đăng ký</a></li>
                    <li class="nav-item"><a class="nav-link" href="thanhtoan.php">Thanh toán</a></li>
                </ul>
                <a href="/Quan_Ly_Su_Kien/app/views/auth/logout.php" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Đăng ký sự kiện</h4>
            </div>
            <div class="card-body p-4">
                <?php if ($thongBao): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $thongBao; ?>
                        <small>(Đang chuyển hướng đến trang thanh toán...)</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($loiThongBao): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $loiThongBao; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" id="eventForm" onsubmit="return validateForm()">
                    <div class="row">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" name="ten_kh" class="form-control" required maxlength="45">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="sdt" class="form-control" required pattern="[0-9]{10}" title="Số điện thoại phải gồm 10 chữ số">
                        </div>
                    </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên sự kiện <span class="text-danger">*</span></label>
                            <input type="text" name="ten_sk" class="form-control" required maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại sự kiện <span class="text-danger">*</span></label>
                            <select name="loai_sk" class="form-select" required>
                                <option value="" disabled selected>Chọn loại sự kiện</option>
                                <option value="sinhnhat">Sinh Nhật</option>
                                <option value="workshop">Workshop</option>
                                <option value="hoithao">Hội Thảo</option>
                                <option value="teambuilding">Team Building</option>
                                <option value="ramatsp">Ra mắt sản phẩm</option>
                                <option value="tieccuoi">Tiệc cưới</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nơi tổ chức <span class="text-danger">*</span></label>
                        <input type="text" name="noi_to_chuc" class="form-control" required maxlength="255">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="ngay_bd" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="ngay_kt" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số người tham gia <span class="text-danger">*</span></label>
                        <input type="number" name="nguoi_tham_gia" class="form-control" required min="1" max="1000">
                    </div>
                    <div class="text-end">
                        <button type="submit" name="dang_ky_su_kien" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Đăng ký sự kiện
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            const form = document.getElementById('eventForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            const sdt = form.querySelector('input[name="sdt"]');
            if (sdt && !/^\d{10}$/.test(sdt.value)) {
                isValid = false;
                sdt.classList.add('is-invalid');
            }

            const nguoiThamGia = form.querySelector('input[name="nguoi_tham_gia"]');
            if (nguoiThamGia && (nguoiThamGia.value < 1 || nguoiThamGia.value > 1000)) {
                isValid = false;
                nguoiThamGia.classList.add('is-invalid');
            }

            return isValid;
        }
    </script>
</body>
</html>
?>