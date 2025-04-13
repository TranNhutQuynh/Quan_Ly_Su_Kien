document.getElementById('confirmPayment').addEventListener('click', function () {
  // Gọi API hoặc xử lý logic gì đó ở đây

  // Hiển thị modal
  const successModal = new bootstrap.Modal(document.getElementById('successModal'));
  successModal.show();

  // Chuyển hướng sau khi hiển thị modal
  setTimeout(() => {
    window.location.href = "/Quan_Ly_Su_Kien/app/views/NguoiDung/thanhtoan.php";
  }, 2000);
});
