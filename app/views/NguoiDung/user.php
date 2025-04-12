<?php
// Nhúng model và controller
include_once __DIR__ . "/../../models/DKsukien.php";
include __DIR__ . "/../../controllers/NguoiDungController.php";

// Tạo đối tượng controller
$controller = new NguoiDungController();

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller->DKSuKien();
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý sự kiện - Trang người dùng</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../public/assets/CSS/user.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
          <i class="fas fa-calendar-check me-2"></i>Quản lý Sự kiện
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#viewEvents">
                <i class="fas fa-list me-1"></i> Sự kiện đã đăng ký
              </a>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <span class="user-welcome">
              <i class="fas fa-user-circle me-1"></i> Xin chào, Nguyễn Văn A
            </span>
            <a href="phanquyen.html" class="btn btn-light btn-sm ms-2">
              <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container mt-4">
      <div class="tab-content">
        <!-- Đăng ký sự kiện -->
        <div class="tab-pane fade show active" id="registerEvent">
          <div class="container">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Đăng ký sự kiện mới</h5>
              </div>
              <div class="card-body">
                <form id="eventRegistrationForm" action="../app/controllers/NguoiDungController.php" method="POST">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Tên người đăng ký</label>
                      <input name="ten_kh" type="text" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Số điện thoại</label>
                      <input name="sdt" type="tel" class="form-control" pattern="[0-9]{10}" required />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label" >Nơi tổ chức</label>
                      <input name="noi_to_chuc" type="text" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Tên sự kiện</label>
                      <input name="ten_sk" type="text" class="form-control" required />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label" >Thời gian bắt đầu</label>
                      <input name="ngay_bd" type="datetime-local" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Thời gian kết thúc</label>
                      <input name="ngay_kt" type="datetime-local" class="form-control" required />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Loại sự kiện</label>
                      <select class="form-select" id="eventType" name="loai_sk" required>
                        <option value="">-- Chọn loại sự kiện --</option>
                        <option value="hoinghi" data-price="15000000">Hội nghị</option>
                        <option value="tieccuoi" data-price="45000000">Tiệc cưới</option>
                        <option value="sinhnhat" data-price="5000000">Sinh nhật</option>
                        <option value="workshop" data-price="8500000">Workshop</option>
                        <option value="hoithao" data-price="12000000">Hội thảo</option>
                        <option value="teambuilding" data-price="20000000">Team Building</option>
                        <option value="ramatsp" data-price="30000000">Ra mắt sản phẩm</option>
                        <option value="khac" data-price="10000000">Khác</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Số lượng người tham dự (dự kiến)</label>
                      <input name="nguoi_tham_gia" type="number" class="form-control" id="attendees" min="1" required />
                    </div>
                  </div>
                  <div class="mb-3" id="priceInfoContainer" style="display: none">
                    <div class="alert alert-info">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Chi phí dự kiến:</h6>
                          <p class="mb-0" id="priceDetails">
                            Giá cơ bản: <span id="basePrice">0</span> VNĐ
                          </p>
                        </div>
                        <div>
                          <h5 class="mb-0 text-primary" id="totalPrice">0 VNĐ</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mô tả chi tiết sự kiện</label>
                    <textarea class="form-control" rows="3"></textarea>
                  </div>
                  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-light"><i class="fas fa-redo me-1"></i> Làm mới</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Đăng ký sự kiện</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Xem sự kiện đã đăng ký -->
        <div class="tab-pane fade" id="viewEvents">
          <div class="container">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách sự kiện đã đăng ký</h5>
                <select class="form-select form-select-sm" aria-label="DSSK">
                  <option value="all">Tất cả sự kiện</option>
                  <option value="pending">Chờ xác nhận</option>
                  <option value="confirmed">Đã xác nhận</option>
                  <option value="completed">Đã hoàn thành</option>
                  <option value="canceled">Đã hủy</option>
                </select>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Mã sự kiện</th>
                        <th>Tên sự kiện</th>
                        <th>Loại</th>
                        <th>Thời gian</th>
                        <th>Địa điểm</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>SK001</td>
                        <td>Hội nghị khoa học</td>
                        <td>Hội nghị</td>
                        <td>15/04/2025 08:00 - 17:00</td>
                        <td>Trung tâm Hội nghị Quốc gia</td>
                        <td>15,000,000 VNĐ</td>
                        <td><span class="event-status status-confirmed">Đã xác nhận</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailModal">
                            <i class="fas fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>SK002</td>
                        <td>Tiệc cưới Nguyễn Văn A</td>
                        <td>Tiệc cưới</td>
                        <td>20/05/2025 18:00 - 22:00</td>
                        <td>Khách sạn Metropole</td>
                        <td>45,000,000 VNĐ</td>
                        <td><span class="event-status status-pending">Chờ xác nhận</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailModal">
                            <i class="fas fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>SK003</td>
                        <td>Workshop Kỹ năng giao tiếp</td>
                        <td>Workshop</td>
                        <td>10/04/2025 13:00 - 17:00</td>
                        <td>Trung tâm đào tạo XYZ</td>
                        <td>8,500,000 VNĐ</td>
                        <td><span class="event-status status-completed">Đã hoàn thành</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#eventDetailModal">
                            <i class="fas fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
        </div>
    <script src="/Quan_Ly_Su_Kien/public/assets/JS/user.js"></script>
  </body>
</html>
