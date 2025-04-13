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
