<!-- app/views/QuanLy/export.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xuất báo cáo - Hệ thống Quản lý Sự kiện</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="public/assets/CSS/quanly.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>Quản lý Sự kiện</h2>
      </div>
      <ul class="sidebar-menu">
        <li>
          <a href="index.php?action=events" id="eventsLink">
            <i>🎭</i>
            <span>Quản lý Sự kiện</span>
          </a>
        </li>
        <li>
          <a href="index.php?action=payments" id="paymentLink">
            <i>💰</i>
            <span>Quản lý Thanh toán</span>
          </a>
        </li>
        <li>
          <a href="index.php?action=export" id="exportLink" class="active">
            <i>📤</i>
            <span>Xuất báo cáo</span>
          </a>
        </li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <div class="header">
        <h1>Xuất báo cáo thống kê</h1>
        <div class="user-info d-flex align-items-center">
          <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
          <span>Admin</span>
        </div>
      </div>

      <!-- Export Section -->
      <section id="exportSection">
        <div id="alertContainer"></div>
        
        <div class="export-options">
          <h3 class="mb-4">Tùy chọn xuất báo cáo</h3>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="reportType" class="form-label">Loại báo cáo</label>
                <select id="reportType" class="form-select">
                  <option value="">-- Chọn loại báo cáo --</option>
                  <option value="revenue">Báo cáo doanh thu</option>
                  <option value="event">Báo cáo số lượng sự kiện</option>
                  <option value="customer">Báo cáo số lượng khách hàng</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row" id="periodContainer" class="d-none">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="reportPeriod" class="form-label">Kỳ báo cáo</label>
                <select id="reportPeriod" class="form-select">
                  <option value="">-- Chọn kỳ báo cáo --</option>
                  <option value="month">Theo tháng</option>
                  <option value="year">Theo năm</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row" id="yearContainer" class="d-none">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="reportYear" class="form-label">Năm</label>
                <select id="reportYear" class="form-select">
                  <!-- Các năm sẽ được tạo bằng JavaScript -->
                </select>
              </div>
            </div>
          </div>
          
          <div class="mt-4">
            <button id="exportBtn" class="btn btn-primary">Xuất báo cáo</button>
          </div>
        </div>
        
        <div class="card mt-4">
          <div class="card-header">
            <h4 class="card-title mb-0">Hướng dẫn xuất báo cáo</h4>
          </div>
          <div class="card-body">
            <p>Để xuất báo cáo, vui lòng thực hiện theo các bước sau:</p>
            <ol>
              <li>Chọn loại báo cáo bạn muốn xuất</li>
              <li>Chọn kỳ báo cáo (theo tháng hoặc theo năm)</li>
              <li>Nếu chọn báo cáo theo tháng, hãy chọn năm cho báo cáo</li>
              <li>Nhấp vào nút "Xuất báo cáo"</li>
              <li>File Excel sẽ được tải về máy tính của bạn</li>
            </ol>
            <p><strong>Lưu ý:</strong> Các báo cáo được xuất dưới định dạng Excel (.xls) và có thể mở bằng Microsoft Excel hoặc các phần mềm bảng tính khác.</p>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="public/assets/JS/export.js"></script>
</body>
</html>