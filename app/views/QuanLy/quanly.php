<?php
session_start();
require_once __DIR__ . '/../../controllers/QuanLyController.php';

$controller = new QuanLyController();

// Xử lý các hành động
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'update_payment' && isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];
        $status = $_POST['status'];
        $method = $_POST['payment_method'] ?? null;
        $note = $_POST['note'] ?? null;
        
        if ($controller->updatePaymentStatus($eventId, $status, $method, $note)) {
            $message = "Cập nhật trạng thái thanh toán thành công!";
        } else {
            $error = "Có lỗi xảy ra khi cập nhật trạng thái thanh toán!";
        }
    } elseif ($_GET['action'] === 'export_report' && isset($_POST['report_type'])) {
        $type = $_POST['report_type'];
        $year = $_POST['report_year'] ?? null;
        $controller->exportReport($type, $year);
    }
}

// Lấy tham số tìm kiếm và lọc
$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Lấy dữ liệu thanh toán
$payments = $controller->getPaymentList($status, $search, $page);
$totalEvents = $controller->countEvents($status, $search);
$totalPages = ceil($totalEvents / 10);

// Lấy danh sách năm cho báo cáo
$years = $controller->getYearsList();
$currentYear = date('Y');

// Lấy số liệu thống kê tổng quan
$stats = $controller->getOverallStats();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hệ thống Quản lý Sự kiện</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="/Quan_Ly_Su_Kien/public/assets/CSS/quanly.css">
  
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Navigation -->
      <aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
        <div class="sidebar-header p-3 border-bottom">
          <h3 class="text-center">Quản lý Sự kiện</h3>
        </div>
        <ul class="sidebar-menu nav flex-column">
          <li class="nav-item">
            <a href="#" id="paymentLink" class="nav-link active">
              <i class="fas fa-money-bill-wave"></i>
              <span>Quản lý Thanh toán</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" id="statsLink" class="nav-link">
              <i class="fas fa-chart-bar"></i>
              <span>Thống kê doanh thu</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" id="reportLink" class="nav-link">
              <i class="fas fa-file-export"></i>
              <span>Xuất báo cáo</span>
            </a>
          </li>
          <li class="nav-item mt-5">
            <a href="../../views/auth/logout.php" class="nav-link text-danger">
              <i class="fas fa-sign-out-alt"></i>
              <span>Đăng xuất</span>
            </a>
          </li>
        </ul>
      </aside>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1>Hệ thống Quản lý Sự kiện</h1>
          <div class="user-info d-flex align-items-center">
            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
            <span>Admin</span>
          </div>
        </div>
        
        <?php if (isset($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $message ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Payment Management Section -->
        <section id="paymentSection">
          <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
              <h2 class="card-title h5 mb-0">Danh sách Thanh toán</h2>
              <div class="toolbar d-flex gap-2">
                <form action="" method="GET" class="d-flex gap-2">
                  <div class="input-group">
                    <input type="text" name="search" id="searchInput" class="form-control" 
                           placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <select name="status" id="filterStatus" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Tất cả</option>
                    <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="unpaid" <?= $status === 'unpaid' ? 'selected' : '' ?>>Chưa thanh toán</option>
                  </select>
                </form>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="paymentTable" class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Mã SK</th>
                      <th>Tên sự kiện</th>
                      <th>Khách hàng</th>
                      <th>Ngày tổ chức</th>
                      <th>Loại sự kiện</th>
                      <th>Tổng tiền</th>
                      <th>Trạng thái</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($payments)): ?>
                    <tr>
                      <td colspan="8" class="text-center">Không có dữ liệu</td>
                    </tr>
                    <?php else: ?>
                      <?php foreach ($payments as $payment): ?>
                      <tr>
                        <td><?= $payment['MA_SK'] ?></td>
                        <td><?= htmlspecialchars($payment['TEN_SK']) ?></td>
                        <td><?= htmlspecialchars($payment['TEN_KH']) ?></td>
                        <td><?= $controller->formatDateTime($payment['NGAY_BD']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($payment['LOAI_SK'])) ?></td>
                        <td><?= $controller->formatCurrency($payment['GIA_TIEN'] ?? 0) ?></td>
                        <td>
                          <?php if (isset($payment['TRANG_THAI']) && $payment['TRANG_THAI'] == 'Đã thanh toán'): ?>
                            <span class="badge bg-success">Đã thanh toán</span>
                          <?php else: ?>
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <button class="btn btn-sm btn-info" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#detailModal" 
                                  data-event-id="<?= $payment['MA_SK'] ?>">
                            <i class="fas fa-eye"></i>
                          </button>
                          
                          <button class="btn btn-sm btn-primary" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#paymentModal" 
                                  data-event-id="<?= $payment['MA_SK'] ?>">
                            <i class="fas fa-money-bill-wave"></i>
                          </button>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination -->
              <?php if ($totalPages > 1): ?>
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                  <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page-1 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>
                  
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>">
                      <?= $i ?>
                    </a>
                  </li>
                  <?php endfor; ?>
                  
                  <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page+1 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                </ul>
              </nav>
              <?php endif; ?>
            </div>
          </div>
        </section>

        <!-- Statistics Section -->
        <section id="statisticsSection">
          <div class="card shadow mb-4">
            <div class="card-header bg-white">
              <h2 class="card-title h5 mb-0">Thống kê doanh thu</h2>
            </div>
            <div class="card-body">
              <div class="dashboard-stats">
                <div class="stat-card bg-info bg-opacity-10">
                  <div class="stat-icon text-info">
                    <i class="fas fa-calendar-check"></i>
                  </div>
                  <div class="stat-info">
                    <h3><?= $stats['total_events'] ?? 0 ?></h3>
                    <p>Tổng số sự kiện</p>
                  </div>
                </div>
                <div class="stat-card bg-success bg-opacity-10">
                  <div class="stat-icon text-success">
                    <i class="fas fa-money-bill-wave"></i>
                  </div>
                  <div class="stat-info">
                    <h3><?= $controller->formatCurrency($stats['total_revenue'] ?? 0) ?></h3>
                    <p>Tổng doanh thu</p>
                  </div>
                </div>
                <div class="stat-card bg-warning bg-opacity-10">
                  <div class="stat-icon text-warning">
                    <i class="fas fa-hourglass-half"></i>
                  </div>
                  <div class="stat-info">
                    <h3><?= $stats['pending_payments'] ?? 0 ?></h3>
                    <p>Chưa thanh toán</p>
                  </div>
                </div>
                <div class="stat-card bg-danger bg-opacity-10">
                  <div class="stat-icon text-danger">
                    <i class="fas fa-calendar-day"></i>
                  </div>
                  <div class="stat-info">
                    <h3><?= $stats['upcoming_events'] ?? 0 ?></h3>
                    <p>Sự kiện sắp tới</p>
                  </div>
                </div>
              </div>
              
              <div class="chart-container">
                <canvas id="revenueChart"></canvas>
              </div>
            </div>
          </div>
        </section>

        <!-- Report Export Section -->
        <section id="reportSection">
          <div class="card shadow">
            <div class="card-header bg-white">
              <h2 class="card-title h5 mb-0">Xuất báo cáo</h2>
            </div>
            <div class="card-body">
              <form action="?action=export_report" method="POST">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="reportType" class="form-label">Loại báo cáo</label>
                    <select id="reportType" name="report_type" class="form-select" required>
                      <option value="" selected disabled>Chọn loại báo cáo</option>
                      <option value="monthly">Báo cáo doanh thu theo tháng</option>
                      <option value="event_type">Báo cáo theo loại sự kiện</option>
                      <option value="customer">Báo cáo theo khách hàng</option>
                      <option value="payment_method">Báo cáo theo phương thức thanh toán</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="reportYear" class="form-label">Năm</label>
                    <select id="reportYear" name="report_year" class="form-select" required>
                      <?php foreach ($years as $year): ?>
                      <option value="<?= $year ?>" <?= $year == $currentYear ? 'selected' : '' ?>><?= $year ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-export me-2"></i>Xuất báo cáo
                  </button>
                </div>
              </form>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Payment Detail Modal -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Chi tiết thanh toán</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <h6 class="fw-bold">Thông tin sự kiện</h6>
                <table class="table table-sm">
                  <tr>
                    <th style="width: 35%">Mã sự kiện:</th>
                    <td id="detail-event-id"></td>
                  </tr>
                  <tr>
                    <th>Tên sự kiện:</th>
                    <td id="detail-event-name"></td>
                  </tr>
                  <tr>
                    <th>Loại sự kiện:</th>
                    <td id="detail-event-type"></td>
                  </tr>
                  <tr>
                    <th>Thời gian:</th>
                    <td id="detail-event-time"></td>
                  </tr>
                  <tr>
                    <th>Địa điểm:</th>
                    <td id="detail-event-location"></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <h6 class="fw-bold">Thông tin thanh toán</h6>
                <table class="table table-sm">
                  <tr>
                    <th style="width: 35%">Khách hàng:</th>
                    <td id="detail-customer-name"></td>
                  </tr>
                  <tr>
                    <th>Tổng tiền:</th>
                    <td id="detail-total-amount"></td>
                  </tr>
                  <tr>
                    <th>Trạng thái:</th>
                    <td id="detail-payment-status"></td>
                  </tr>
                  <tr>
                    <th>Phương thức:</th>
                    <td id="detail-payment-method"></td>
                  </tr>
                  <tr>
                    <th>Ngày thanh toán:</th>
                    <td id="detail-payment-date"></td>
                  </tr>
                  <tr>
                    <th>Ghi chú:</th>
                    <td id="detail-payment-note"></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Update Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Cập nhật thanh toán</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="?action=update_payment" method="POST">
          <div class="modal-body">
            <input type="hidden" id="payment-event-id" name="event_id">
            
            <div class="mb-3">
              <label for="paymentStatus" class="form-label">Trạng thái thanh toán</label>
              <select id="paymentStatus" name="status" class="form-select" required>
                <option value="1">Đã thanh toán</option>
                <option value="0">Chưa thanh toán</option>
              </select>
            </div>
            
            <div id="paymentMethodGroup" class="mb-3">
              <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
              <select id="paymentMethod" name="payment_method" class="form-select">
                <option value="">Chọn phương thức</option>
                <option value="cash">Tiền mặt</option>
                <option value="bank_transfer">Chuyển khoản</option>
                <option value="credit_card">Thẻ tín dụng</option>
                <option value="momo">Ví MoMo</option>
                <option value="zalopay">ZaloPay</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label for="paymentNote" class="form-label">Ghi chú</label>
              <textarea id="paymentNote" name="note" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap & jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    $(document).ready(function () {
  // Navigation
  $("#paymentLink").click(function (e) {
    e.preventDefault();
    $(".sidebar-menu .nav-link").removeClass("active");
    $(this).addClass("active");
    $("#paymentSection").show();
    $("#statisticsSection, #reportSection").hide();
  });

  $("#statsLink").click(function (e) {
    e.preventDefault();
    $(".sidebar-menu .nav-link").removeClass("active");
    $(this).addClass("active");
    $("#statisticsSection").show();
    $("#paymentSection, #reportSection").hide();
    initChart();
  });

  $("#reportLink").click(function (e) {
    e.preventDefault();
    $(".sidebar-menu .nav-link").removeClass("active");
    $(this).addClass("active");
    $("#reportSection").show();
    $("#paymentSection, #statisticsSection").hide();
  });

  // Payment status change
  $("#paymentStatus").change(function () {
    if ($(this).val() == "1") {
      $("#paymentMethodGroup").show();
    } else {
      $("#paymentMethodGroup").hide();
    }
  });

  // Payment Modal
  $("#paymentModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var eventId = button.data("event-id");
    var modal = $(this);

    // Reset form
    modal.find("form")[0].reset();

    // Set event ID
    modal.find("#payment-event-id").val(eventId);

    // Load payment details via AJAX
    $.ajax({
      url: "/Quan_Ly_Su_Kien/app/ajax/get_payment.php",
      type: "GET",
      data: { event_id: eventId },
      dataType: "json",
      success: function (data) {
        $("#paymentStatus").val(data.da_thanh_toan);
        $("#paymentMethod").val(data.phuong_thuc_tt);
        $("#paymentNote").val(data.ghi_chu);

        // Toggle payment method field
        if (data.da_thanh_toan == "1") {
          $("#paymentMethodGroup").show();
        } else {
          $("#paymentMethodGroup").hide();
        }
      },
      error: function () {
        alert("Không thể tải thông tin thanh toán");
      },
    });
  });

  // Detail Modal
  $("#detailModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var eventId = button.data("event-id");
    var modal = $(this);

    // Load event details via AJAX
    $.ajax({
      url: "/Quan_Ly_Su_Kien/app/ajax/get_event_details.php",
      type: "GET",
      data: { event_id: eventId },
      dataType: "json",
      success: function (data) {
        $("#detail-event-id").text(data.ma_sk);
        $("#detail-event-name").text(data.ten_sk);
        $("#detail-event-type").text(data.loai_sk);
        $("#detail-event-time").text(data.thoi_gian);
        $("#detail-event-location").text(data.dia_diem);
        $("#detail-customer-name").text(data.ten_kh);
        $("#detail-total-amount").text(data.gia_tien);

        // Set payment status with badge
        if (data.da_thanh_toan == 1) {
          $("#detail-payment-status").html(
            '<span class="badge bg-success">Đã thanh toán</span>'
          );
        } else {
          $("#detail-payment-status").html(
            '<span class="badge bg-warning text-dark">Chưa thanh toán</span>'
          );
        }

        $("#detail-payment-method").text(data.phuong_thuc_tt || "—");
        $("#detail-payment-date").text(data.ngay_thanh_toan || "—");
        $("#detail-payment-note").text(data.ghi_chu || "—");
      },
      error: function () {
        alert("Không thể tải thông tin sự kiện");
      },
    });
  });

  // Initialize revenue chart function
  function initChart() {
    $.ajax({
      url: "/Quan_Ly_Su_Kien/app/ajax/get_revenue_data.php",
      type: "GET",
      dataType: "json",
      success: function (data) {
        var ctx = document.getElementById("revenueChart").getContext("2d");

        // Destroy existing chart if any
        if (window.revenueChart instanceof Chart) {
          window.revenueChart.destroy();
        }

        window.revenueChart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: data.labels,
            datasets: [
              {
                label: "Doanh thu theo tháng (VNĐ)",
                data: data.values,
                backgroundColor: "rgba(54, 162, 235, 0.5)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function (value) {
                    return new Intl.NumberFormat("vi-VN").format(value) + " đ";
                  },
                },
              },
            },
            plugins: {
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return (
                      "Doanh thu: " +
                      new Intl.NumberFormat("vi-VN").format(context.raw) +
                      " đ"
                    );
                  },
                },
              },
            },
          },
        });
      },
      error: function () {
        console.error("Không thể tải dữ liệu doanh thu");
      },
    });
  }
});

  </script>
  
</body>
</html>