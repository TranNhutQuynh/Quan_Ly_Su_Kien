<?php
session_start();
// Hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kết nối đến cơ sở dữ liệu
require_once __DIR__."/../../database.php";

// Biến lưu trữ thông báo lỗi và dữ liệu form
$error = '';
$success = '';
$username = '';

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user_id'])) {
    // Chuyển hướng dựa trên vai trò
    if ($_SESSION['role'] === 'admin') {
        header("Location: /Quan_Ly_Su_Kien/app/views/QuanLy/quanly.php");
    } else {
        header("Location: /Quan_Ly_Su_Kien/app/views/NguoiDung/user.php");
    }
    exit();
}

// Xử lý form đăng ký
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Kiểm tra dữ liệu nhập
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Vui lòng điền đầy đủ thông tin";
    } elseif (strlen($username) < 4) {
        $error = "Tên đăng nhập phải có ít nhất 4 ký tự";
    } elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp";
    } else {
        // Kiểm tra tên đăng nhập đã tồn tại chưa
        $check_username = $conn->prepare("SELECT * FROM account WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $result = $check_username->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại, vui lòng chọn tên khác";
        } else {
            // Tạo tài khoản mới
            $create_user = $conn->prepare("INSERT INTO account (username, password, quyen) VALUES (?, ?, 'user')");
            // Trong môi trường thực tế, bạn nên sử dụng hàm password_hash() để mã hóa mật khẩu
            $create_user->bind_param("ss", $username, $password);
            
            if ($create_user->execute()) {
                $success = "Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.";
                // Xóa dữ liệu form sau khi đăng ký thành công
                $username = '';
            } else {
                $error = "Đã xảy ra lỗi khi tạo tài khoản. Vui lòng thử lại sau.";
            }
            
            $create_user->close();
        }
        
        $check_username->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - QNP Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/assets/CSS/SignUp.css">
</head>
<body>
    <a href="../../views/TrangChu/trangchu.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Quay lại trang chủ
    </a>
    
    <div class="form-container">
        <h2>Đăng Ký Tài Khoản</h2>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="input-group">
                <label for="username">Tên đăng nhập:</label>
                <i class="fas fa-user input-icon"></i>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Nhập tên đăng nhập"
                    value="<?php echo htmlspecialchars($username); ?>"
                    required
                >
                <div class="form-hint">Tên đăng nhập phải có ít nhất 4 ký tự</div>
            </div>
            
            <div class="input-group">
                <label for="password">Mật khẩu:</label>
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Nhập mật khẩu"
                    required
                >
                <div class="form-hint">Mật khẩu phải có ít nhất 6 ký tự</div>
            </div>
            
            <div class="input-group">
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Nhập lại mật khẩu"
                    required
                >
            </div>
            
            <button type="submit" class="signup-btn">Đăng ký ngay</button>
            
            <div class="login-link">
                Đã có tài khoản? <a href="phanquyen.php">Đăng nhập</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>