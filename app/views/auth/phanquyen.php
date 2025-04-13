<?php
session_start();
// Hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kết nối đến cơ sở dữ liệu
require_once __DIR__."/../../database.php";

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

// Biến lưu trữ thông báo lỗi và dữ liệu form
$error = '';
$username = '';

// Xử lý form đăng nhập
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Kiểm tra dữ liệu nhập
    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu";
    } else {
        // Truy vấn từ cơ sở dữ liệu - Sử dụng Prepared Statement để tránh SQL injection
        $stmt = $conn->prepare("SELECT * FROM account WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu (trong môi trường thực tế, bạn nên sử dụng password_hash và password_verify)
            if ($password === $user['password']) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = isset($user['quyen']) ? $user['quyen'] : 'user'; // Mặc định là 'user' nếu không có cột role
                
                // Chuyển hướng dựa trên vai trò
                if ($_SESSION['role'] === 'admin') {
                    header("Location: /Quan_Ly_Su_Kien/app/views/QuanLy/quanly.php");
                    exit;
                } else {
                    header("Location: /Quan_Ly_Su_Kien/app/views/NguoiDung/user.php");
                    exit;
                }
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không chính xác";
            }
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không chính xác";
        }
        
        $stmt->close();
    }
}

// Tạo tài khoản mặc định cho quản lý nếu chưa tồn tại
$admin_username = "admin";
$admin_password = "admin123";

// Kiểm tra xem tài khoản admin đã tồn tại chưa
$check_admin = $conn->prepare("SELECT * FROM account WHERE username = ?");
$check_admin->bind_param("s", $admin_username);
$check_admin->execute();
$admin_result = $check_admin->get_result();

if ($admin_result->num_rows == 0) {
    // Tạo tài khoản admin nếu chưa tồn tại
    $create_admin = $conn->prepare("INSERT INTO account (username, password, quyen) VALUES (?, ?, 'admin')");
    
    // Thêm cột role nếu chưa tồn tại
    $check_column = $conn->query("SHOW COLUMNS FROM account LIKE 'quyen'");
    if ($check_column->num_rows == 0) {
        $conn->query("ALTER TABLE account ADD COLUMN quyen VARCHAR(20) DEFAULT 'user'");
    }

    
    $create_admin->bind_param("ss", $admin_username, $admin_password);
    $create_admin->execute();
    $create_admin->close();
}
$check_admin->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - QNP Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/Quan_Ly_Su_Kien/public/assets/CSS/phanquyen.css">
</head>
<body>
    <a href="../../views/TrangChu/trangchu.php" class="back-to-home">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    
    <div class="login-container">
        <div class="login-image">
            <div class="logo-container">
                <h1>QNP Events</h1>
                <p>Tổ chức sự kiện chuyên nghiệp</p>
            </div>
        </div>
        
        <div class="login-form">
            <div class="login-header">
                <h2>ĐĂNG NHẬP</h2>
                <p>Vui lòng đăng nhập để sử dụng các đặc quyền của bạn</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="input-group">
                    <label for="username">Tên tài khoản</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Nhập tên tài khoản" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Nhớ mật khẩu</label>
                    </div>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="login-button">Đăng nhập</button>
                
                <div class="register-link">
                    Chưa có tài khoản? <a href="signup.php">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>