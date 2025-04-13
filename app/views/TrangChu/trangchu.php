<?php
  session_start();
  // Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Công ty QNP - Chuyên tổ chức sự kiện chuyên nghiệp tại Việt Nam">
  <title>Công ty QNP - Quản lý Sự kiện Chuyên nghiệp</title>
  <link rel="stylesheet" href="/Quan_Ly_Su_Kien/public/assets/css/trangchu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Header với slider ảnh -->
  <header class="hero-header">
    <div class="hero-slider" id="headerSlider">
      <div class="slide active">
        <img src="/Quan_Ly_Su_Kien/public/assets/images/sukien1.jpg" alt="Sự kiện hội nghị" loading="lazy">
      </div>
      <div class="slide">
        <img src="/Quan_Ly_Su_Kien/public/assets/images/sukien2.jpg" alt="Sự kiện doanh nghiệp" loading="lazy">
      </div>
      <div class="slide">
        <img src="/Quan_Ly_Su_Kien/public/assets/images/sukien3.jpg" alt="Tổ chức sự kiện" loading="lazy">
      </div>
      <div class="slider-controls">
        <button class="prev-btn" aria-label="Slide trước"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-dots">
          <span class="dot active" data-slide="0"></span>
          <span class="dot" data-slide="1"></span>
          <span class="dot" data-slide="2"></span>
        </div>
        <button class="next-btn" aria-label="Slide tiếp theo"><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
    <div class="hero-content">
      <h1>Công ty QNP</h1>
      <p>Chuyên tổ chức sự kiện chuyên nghiệp</p>
      <a href="#services" class="cta-button">Khám phá dịch vụ</a>
    </div>
  </header>

  <!-- Navigation bar -->
  <nav class="sticky-nav">
    <div class="container nav-container">
      <a href="#" class="logo">QNP Events</a>
      <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
        <i class="fas fa-bars"></i>
      </button>
      <div class="nav-links" id="navLinks">
        <a href="#" class="active">Trang chủ</a>
        <a href="#services">Dịch vụ</a>
        <a href="#about">Về chúng tôi</a>
        <a href="#featured-events">Sự kiện</a>
        <a href="#contact">Liên hệ</a>
      </div>
      <div class="auth-links" id="authLinks">
        <a href="login.html" id="loginBtn">Đăng nhập</a>
        <a href="signup.html" id="registerBtn">Đăng ký</a>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main>
    <!-- Phần giới thiệu -->
    <section id="about" class="about-section">
      <div class="container">
        <h2 class="section-title">Về Chúng Tôi</h2>
        <div class="about-content">
          <div class="about-image">
            <img id="QNPTeam" src="/Quan_Ly_Su_Kien/public/assets/images/QNP_Event.png" alt="Đội ngũ QNP Events" loading="lazy">
          </div>
          <div class="about-text">
            <h3>QNP Events - Đối tác đáng tin cậy của bạn</h3>
            <p>Với hơn 5 năm kinh nghiệm trong lĩnh vực tổ chức sự kiện, chúng tôi tự hào mang đến những dịch vụ chuyên nghiệp và sáng tạo nhất cho khách hàng.</p>
            <p>Đội ngũ nhân viên giàu kinh nghiệm và nhiệt huyết của chúng tôi luôn sẵn sàng biến mọi ý tưởng của bạn thành hiện thực, từ những buổi hội nghị quy mô lớn đến những bữa tiệc thân mật.</p>
            <div class="stats-container">
              <div class="stat-item">
                <span class="stat-number">500+</span>
                <span class="stat-text">Sự kiện thành công</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">300+</span>
                <span class="stat-text">Khách hàng hài lòng</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">50+</span>
                <span class="stat-text">Chuyên gia sự kiện</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Phần dịch vụ -->
    <section id="services" class="services-section">
      <div class="container">
        <h2 class="section-title">Dịch vụ của chúng tôi</h2>
        <p class="section-description">Chúng tôi cung cấp đa dạng các dịch vụ tổ chức sự kiện chuyên nghiệp</p>
        <div class="services-grid">
          <div class="service-card hover-scale">
            <div class="service-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <h3>Hội nghị & Hội thảo</h3>
            <p>Tổ chức hội nghị, hội thảo chuyên nghiệp với đầy đủ trang thiết bị hiện đại cùng đội ngũ nhân viên nhiều kinh nghiệm.</p>
            <a href="#" class="service-link">Tìm hiểu thêm <i class="fas fa-arrow-right"></i></a>
          </div>
          <div class="service-card hover-scale">
            <div class="service-icon">
              <i class="fas fa-glass-cheers"></i>
            </div>
            <h3>Tiệc cưới & Sinh nhật</h3>
            <p>Dịch vụ tổ chức tiệc cưới, sinh nhật với nhiều phong cách khác nhau, đáp ứng mọi nhu cầu và ngân sách của khách hàng.</p>
            <a href="#" class="service-link">Tìm hiểu thêm <i class="fas fa-arrow-right"></i></a>
          </div>
          <div class="service-card hover-scale">
            <div class="service-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <h3>Workshop & Sự kiện doanh nghiệp</h3>
            <p>Tổ chức workshop, sự kiện ra mắt sản phẩm, team building và các hoạt động doanh nghiệp một cách chuyên nghiệp.</p>
            <a href="#" class="service-link">Tìm hiểu thêm <i class="fas fa-arrow-right"></i></a>
          </div>
          <div class="service-card hover-scale">
            <div class="service-icon">
              <i class="fas fa-bullhorn"></i>
            </div>
            <h3>Marketing & Quảng cáo</h3>
            <p>Dịch vụ quảng bá sự kiện, thiết kế và sản xuất tài liệu marketing, quảng cáo trên các phương tiện truyền thông.</p>
            <a href="#" class="service-link">Tìm hiểu thêm <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Phần sự kiện nổi bật -->
    <section id="featured-events" class="featured-events-section">
      <div class="container">
        <h2 class="section-title">Sự kiện nổi bật</h2>
        <p class="section-description">
          Những sự kiện đặc sắc đã được công ty QNP tổ chức thành công
        </p>
        <div class="events-grid">
          <div class="event-card hover-scale">
            <div class="event-img">
              <img src="https://media-efl.neu.edu.vn/uploads/2025/03/05/kh-1741136417.JPG" alt="Hội nghị Khoa học Công nghệ 2025" loading="lazy">
              <div class="event-date">
                <span class="day">15</span>
                <span class="month">Tháng 1</span>
              </div>
            </div>
            <div class="event-content">
              <h3>Hội nghị Khoa học Công nghệ 2025</h3>
              <p>Hội nghị quy tụ các chuyên gia hàng đầu trong lĩnh vực công nghệ với hơn 500 khách mời tham dự.</p>
              <div class="event-meta">
                <span><i class="fas fa-map-marker-alt"></i> Trung tâm Hội nghị Quốc gia</span>
                <a href="#" class="event-link">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <div class="event-card hover-scale">
            <div class="event-img">
              <img src="https://api.royalpalacesg.com.vn/v1/image/7ff0aeac-b417-443e-939c-27cf9947a664.jpeg" alt="Tiệc cưới Royal Palace" loading="lazy">
              <div class="event-date">
                <span class="day">28</span>
                <span class="month">Tháng 2</span>
              </div>
            </div>
            <div class="event-content">
              <h3>Tiệc cưới Royal Palace</h3>
              <p>Tiệc cưới sang trọng với phong cách hoàng gia, được tổ chức tại khuôn viên ngoài trời rộng 1000m².</p>
              <div class="event-meta">
                <span><i class="fas fa-map-marker-alt"></i> Royal Palace</span>
                <a href="#" class="event-link">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <div class="event-card hover-scale">
            <div class="event-img">
              <img src="https://bssc.vn/wp-content/uploads/2023/02/Artboard-21bizzi-500-2.png" alt="Workshop Khởi nghiệp 2025" loading="lazy">
              <div class="event-date">
                <span class="day">10</span>
                <span class="month">Tháng 3</span>
              </div>
            </div>
            <div class="event-content">
              <h3>Workshop Khởi nghiệp 2025</h3>
              <p>Workshop chia sẻ kinh nghiệm từ những người thành công trong lĩnh vực khởi nghiệp.</p>
              <div class="event-meta">
                <span><i class="fas fa-map-marker-alt"></i> Trung tâm Đổi mới Sáng tạo</span>
                <a href="#" class="event-link">Xem chi tiết</a>
              </div>
            </div>
          </div>
        </div>
        <div class="view-all-container">
          <a href="#" class="view-all-btn">Xem tất cả sự kiện</a>
        </div>
      </div>
    </section>

    <!-- Phần đánh giá từ khách hàng -->
    <section class="testimonials-section">
      <div class="container">
        <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
        <div class="testimonials-slider" id="testimonialsSlider">
          <div class="testimonial-slide active">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <p>QNP Events đã giúp công ty chúng tôi tổ chức thành công sự kiện kỷ niệm 10 năm thành lập. Đội ngũ nhân viên chuyên nghiệp và sáng tạo đã mang đến một buổi lễ đáng nhớ.</p>
              </div>
              <div class="testimonial-author">
                <img src="https://svgsilh.com/svg/1299805.svg" alt="Khách hàng" loading="lazy">
                <div class="author-info">
                  <h4>Nguyễn Văn A</h4>
                  <p>Giám đốc Công ty ABC</p>
                </div>
              </div>
            </div>
          </div>
          <div class="testimonial-slide">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <p>Đám cưới của chúng tôi đã trở nên hoàn hảo nhờ sự giúp đỡ của QNP Events. Từ việc lên ý tưởng đến thực hiện, mọi thứ đều được chăm chút tỉ mỉ và chuyên nghiệp.</p>
              </div>
              <div class="testimonial-author">
                <img src="https://svgsilh.com/svg/1299805.svg" alt="Khách hàng" loading="lazy">
                <div class="author-info">
                  <h4>Trần Thị B</h4>
                  <p>Khách hàng</p>
                </div>
              </div>
            </div>
          </div>
          <div class="testimonial-slide">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <p>Workshop do QNP Events tổ chức đã mang lại hiệu quả cao và nhiều trải nghiệm thú vị cho nhân viên công ty chúng tôi. Chắc chắn sẽ tiếp tục hợp tác trong tương lai.</p>
              </div>
              <div class="testimonial-author">
                <img src="https://svgsilh.com/svg/1299805.svg" alt="Khách hàng" loading="lazy">
                <div class="author-info">
                  <h4>Phạm Văn C</h4>
                  <p>Trưởng phòng Nhân sự Công ty XYZ</p>
                </div>
              </div>
            </div>
          </div>
          <div class="slider-controls">
            <button class="prev-btn" aria-label="Đánh giá trước"><i class="fas fa-chevron-left"></i></button>
            <div class="slider-dots">
              <span class="dot active" data-slide="0"></span>
              <span class="dot" data-slide="1"></span>
              <span class="dot" data-slide="2"></span>
            </div>
            <button class="next-btn" aria-label="Đánh giá tiếp theo"><i class="fas fa-chevron-right"></i></button>
          </div>
        </div>
      </div>
    </section>

    <!-- Phần liên hệ -->
    <section id="contact" class="contact-section">
      <div class="container">
        <h2 class="section-title">Liên hệ với chúng tôi</h2>
        <div class="contact-container">
          <div class="contact-info">
            <div class="contact-item">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <h3>Địa chỉ</h3>
                <p>Số 123, Đường ABC, Quận 1, TP.HCM</p>
              </div>
            </div>
            <div class="contact-item">
              <i class="fas fa-phone"></i>
              <div>
                <h3>Điện thoại</h3>
                <p>(+84) 123 456 789</p>
              </div>
            </div>
            <div class="contact-item">
              <i class="fas fa-envelope"></i>
              <div>
                <h3>Email</h3>
                <p>info@qnp.com</p>
              </div>
            </div>
            <div class="contact-item">
              <i class="fas fa-clock"></i>
              <div>
                <h3>Giờ làm việc</h3>
                <p>Thứ Hai - Thứ Sáu: 8:00 - 17:30</p>
                <p>Thứ Bảy: 8:00 - 12:00</p>
              </div>
            </div>
          </div>
          <div class="contact-form">
            <form id="contactForm">
              <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone">
              </div>
              <div class="form-group">
                <label for="subject">Chủ đề</label>
                <input type="text" id="subject" name="subject">
              </div>
              <div class="form-group">
                <label for="message">Nội dung</label>
                <textarea id="message" name="message" rows="5" required></textarea>
              </div>
              <button type="submit" class="submit-btn">Gửi thông tin</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>Về QNP Events</h3>
          <p>Công ty QNP Events chuyên tổ chức các sự kiện chuyên nghiệp với nhiều năm kinh nghiệm, mang đến cho khách hàng những trải nghiệm đáng nhớ.</p>
          <div class="social-links">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="footer-section">
          <h3>Dịch vụ</h3>
          <ul class="footer-links">
            <li><a href="#">Hội nghị & Hội thảo</a></li>
            <li><a href="#">Tiệc cưới & Sinh nhật</a></li>
            <li><a href="#">Workshop & Sự kiện doanh nghiệp</a></li>
            <li><a href="#">Marketing & Quảng cáo</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Liên kết nhanh</h3>
          <ul class="footer-links">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#services">Dịch vụ</a></li>
            <li><a href="#about">Về chúng tôi</a></li>
            <li><a href="#featured-events">Sự kiện</a></li>
            <li><a href="#contact">Liên hệ</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Đăng ký nhận tin</h3>
          <p>Đăng ký để nhận thông tin mới nhất về các sự kiện và ưu đãi đặc biệt.</p>
          <div class="newsletter">
            <form id="newsletterForm">
              <input type="email" placeholder="Nhập email của bạn" class="newsletter-input" required>
              <button type="submit" class="newsletter-btn">Đăng ký</button>
            </form>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 QNP Events. Bảo lưu mọi quyền.</p>
        <p>Thiết kế bởi <a href="#" class="designer-link">QNP Team</a></p>
      </div>
    </div>
  </footer>

  <script src="/Quan_Ly_Su_Kien/public/assets/js/trangchu.js"></script>
</body>
</html>