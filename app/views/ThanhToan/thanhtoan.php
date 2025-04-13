<?php
  session_start();
  require_once __DIR__."/../../controllers/ThanhToanController.php";

  $controller = new ThanhToanController();
  $thongTinTT = $controller->thongtinTT();
  
  // Format thông tin
  $tongTien = $controller->formatCurrency($thongTinTT['gia_tien']);
  $tienCocCanThanhToan = $controller->formatCurrency($thongTinTT['gia_tien'] * 0.5);
  $ngayBatDau = $controller->formatDateTime($thongTinTT['ngay_bd']);
  $ngayKetThuc = $controller->formatDateTime($thongTinTT['ngay_kt']);
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán sự kiện</title>
    <!-- CSS Bootstrap và Font Awesome -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="/Quan_Ly_Su_Kien/public/assets/CSS/thanhtoan.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
          <i class="fas fa-calendar-check me-2"></i>Quản lý Sự kiện
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="/Quan_Ly_Su_Kien/app/views/NguoiDung/user.php">
                <i class="fas fa-plus-circle me-1"></i>Đăng ký sự kiện
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Quan_Ly_Su_Kien/app/views/NguoiDung/user.php#viewEvents">
                <i class="fas fa-list me-1"></i>Sự kiện đã đăng ký
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">
                <i class="fas fa-credit-card me-1"></i>Thanh toán
              </a>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <span class="user-welcome me-2">
              <i class="fas fa-user-circle me-1"></i>Xin chào, <?= $thongTinTT['ten_kh'] ?>
            </span>
            <a href="/Quan_Ly_Su_Kien/app/views/TrangChu/trangchu.php" class="btn btn-light btn-sm">
              <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Nội dung thanh toán -->
    <div class="container py-5">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
          <h3 class="mb-0">
            <i class="fas fa-credit-card me-2"></i>Thanh toán sự kiện
          </h3>
        </div>
        <div class="card-body">
          <!-- Thông tin sự kiện -->
          <div class="alert alert-info mb-4">
            <div class="row">
              <div class="col-md-6">
                <p class="mb-1"><strong>Mã sự kiện:</strong> <?= $thongTinTT['ma_sk'] ?></p>
                <p class="mb-1"><strong>Tên sự kiện:</strong> <?= $thongTinTT['ten_sk'] ?></p>
                <p class="mb-1"><strong>Loại sự kiện:</strong> <?= ucfirst($thongTinTT['loai_sk']) ?></p>
                <p class="mb-1"><strong>Người đăng ký:</strong> <?= $thongTinTT['ten_kh'] ?></p>
                <p class="mb-1"><strong>Số điện thoại:</strong> <?= $thongTinTT['sdt'] ?></p>
                <p class="mb-1"><strong>Địa điểm:</strong> <?= $thongTinTT['noi_to_chuc'] ?></p>
              </div>
              <div class="col-md-6">
                <p class="mb-1"><strong>Thời gian bắt đầu:</strong> <?= $ngayBatDau ?></p>
                <p class="mb-1"><strong>Thời gian kết thúc:</strong> <?= $ngayKetThuc ?></p>
                <p class="mb-1"><strong>Số người tham gia:</strong> <?= $thongTinTT['nguoi_tham_gia'] ?> người</p>
                <p class="mb-1"><strong>Tổng số tiền:</strong> <span class="text-danger fw-bold"><?= $tongTien ?></span></p>
                <p class="mb-1"><strong>Cần thanh toán (đặt cọc 50%):</strong> <span class="text-danger fw-bold"><?= $tienCocCanThanhToan ?></span></p>
                <p class="mb-1">
                  <strong>Trạng thái:</strong>
                  <span class="payment-status text-warning fw-bold">Chờ thanh toán</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Progress bar thanh toán -->
          <div class="mb-4">
            <h5 class="text-center mb-2">Tiến độ thanh toán</h5>
            <div class="progress" style="height: 25px;">
              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
          </div>

          <!-- Phương thức thanh toán -->
          <h4 class="mb-4">
            <i class="fas fa-wallet me-2"></i>Chọn phương thức thanh toán
          </h4>
          <div class="row">
            <!-- Tiền mặt -->
            <div class="col-md-6">
              <div class="card border-primary mb-3">
                <div class="card-header">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="paymentMethod"
                      id="cashMethod"
                      checked
                    />
                    <label class="form-check-label" for="cashMethod">
                      <i class="fas fa-money-bill-wave me-2"></i>Tiền mặt
                    </label>
                  </div>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="fas fa-map-marker-alt me-2"></i>Thanh toán trực
                    tiếp
                  </h5>
                  <ul class="list-unstyled">
                    <li>
                      <i class="fas fa-building me-2"></i>Số 123 Đường ABC, Quận
                      1, TP.HCM
                    </li>
                    <li>
                      <i class="fas fa-clock me-2"></i>Thứ 2 - Thứ 6: 8:00 -
                      17:00
                    </li>
                    <li><i class="fas fa-phone me-2"></i>028 1234 5678</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Chuyển khoản -->
            <div class="col-md-6">
              <div class="card border-primary mb-3">
                <div class="card-header">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="paymentMethod"
                      id="bankMethod"
                    />
                    <label class="form-check-label" for="bankMethod">
                      <i class="fas fa-university me-2"></i>Chuyển khoản
                    </label>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <h5>
                        <i class="fas fa-info-circle me-2"></i>Thông tin tài
                        khoản
                      </h5>
                      <ul class="list-unstyled">
                        <li>Ngân hàng: Vietcombank</li>
                        <li>Số TK: 123456789</li>
                        <li>Chủ TK: Công Ty XYZ</li>
                        <li>Nội dung: SK<?= $thongTinTT['ma_sk'] ?>_<?= $thongTinTT['ten_kh'] ?></li>
                      </ul>
                    </div>
                    <div class="col-md-6 text-center">
                      <h5><i class="fas fa-qrcode me-2"></i>QR Code</h5>
                      <img
                        src="/api/placeholder/150/150"
                        alt="QR Code Thanh Toán"
                        class="img-fluid mb-2"
                      />
                      <p class="small text-muted">Quét mã để thanh toán</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Thông tin thêm và ghi chú -->
          <div class="mb-4 mt-3">
            <div class="card">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin thanh toán</h5>
              </div>
              <div class="card-body">
                <ul>
                  <li>Vui lòng đặt cọc <strong>50%</strong> tổng chi phí để xác nhận đăng ký sự kiện</li>
                  <li>Số tiền còn lại sẽ được thanh toán sau khi kết thúc sự kiện</li>
                  <li>Trong trường hợp hủy sự kiện, số tiền cọc sẽ không được hoàn lại</li>
                  <li>Vui lòng liên hệ <strong>028 1234 5678</strong> nếu cần hỗ trợ thêm</li>
                </ul>
                <div class="form-floating mt-3">
                  <textarea class="form-control" id="paymentNote" style="height: 100px" placeholder="Ghi chú thanh toán"></textarea>
                  <label for="paymentNote">Ghi chú thanh toán (nếu có)</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Nút hành động thanh toán -->
          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button class="btn btn-secondary btn-lg" id="cancelPayment">
              <i class="fas fa-times me-2"></i>Hủy bỏ
            </button>
            <button class="btn btn-success btn-lg" id="confirmPayment" data-bs-toggle="modal" data-bs-target="#successModal">
              <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Thanh toán thành công -->
    <div class="modal fade" id="successModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">
              <i class="fas fa-check-circle me-2"></i>Thanh toán thành công
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body text-center">
            <i
              class="fas fa-check-circle text-success mb-4"
              style="font-size: 60px"
            ></i>
            <h4>Đã thanh toán thành công!</h4>
            <p>Cảm ơn <strong><?= $thongTinTT['ten_kh'] ?></strong> đã đăng ký và thanh toán!</p>
            <p class="text-muted">Mã giao dịch: #PAY<?= mt_rand(100000, 999999) ?></p>
            <p>Thông tin xác nhận đã được gửi đến email của bạn.</p>
          </div>
          <div class="modal-footer">
            <a href="/Quan_Ly_Su_Kien/app/views/NguoiDung/user.php" class="btn btn-primary">
              <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
            <button class="btn btn-success" data-bs-dismiss="modal">
              <i class="fas fa-print me-2"></i>In biên lai
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Script thanh toán -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút hủy bỏ
        document.getElementById('cancelPayment').addEventListener('click', function() {
          if (confirm('Bạn có chắc muốn hủy thanh toán này?')) {
            window.location.href = '/Quan_Ly_Su_Kien/app/views/NguoiDung/user.php';
          }
        });
        
        // Xử lý chuyển đổi phương thức thanh toán
        const cashMethod = document.getElementById('cashMethod');
        const bankMethod = document.getElementById('bankMethod');
        
        cashMethod.addEventListener('change', function() {
          if (this.checked) {
            // Logic khi chọn thanh toán tiền mặt
            console.log('Đã chọn phương thức thanh toán tiền mặt');
          }
        });
        
        bankMethod.addEventListener('change', function() {
          if (this.checked) {
            // Logic khi chọn thanh toán chuyển khoản
            console.log('Đã chọn phương thức thanh toán chuyển khoản');
          }
        });
        
        // Xử lý nút xác nhận thanh toán
        document.getElementById('confirmPayment').addEventListener('click', function() {
          // Cập nhật progress bar khi xác nhận thanh toán
          document.querySelector('.progress-bar').style.width = '50%';
          document.querySelector('.progress-bar').innerText = '50%';
          
          // Thêm logic lưu thông tin thanh toán ở đây nếu cần
        });
      });
    </script>
  </body>
</html>