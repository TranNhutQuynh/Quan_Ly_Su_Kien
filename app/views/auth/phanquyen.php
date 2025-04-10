<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Đăng Nhập</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/Quan_Ly_Su_Kien/public/assets/CSS/phanquyen.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>

  <body>
    <div class="container mt-3">
      <!--Form người dùng-->
      <div class="form-customer" onclick="toggleForm('customer')">
        <h2>Khách hàng</h2>
        <div class="customer">
          <form action="/action_page.php">
            <div class="mb-3 mt-3">
              <label for="email">Email:</label>
              <input
                type="email"
                class="form-control"
                id="email"
                placeholder="Enter email"
                name="email"
              />
            </div>
            <div class="mb-3">
              <label for="pwd">Password:</label>
              <input
                type="password"
                class="form-control"
                id="pwd"
                placeholder="Enter password"
                name="pswd"
              />
            </div>
            <div class="form-check mb-3">
              <label class="form-check-label">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="remember"
                />
                Remember me
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>

      <!--Form Quản Lý-->
      <div class="form-admin" onclick="toggleForm('admin')">
        <h2>Quản Lý</h2>
        <div class="admin">
          <form action="/action_page.php">
            <div class="mb-3 mt-3">
              <label for="email">Email:</label>
              <input
                type="email"
                class="form-control"
                id="email"
                placeholder="Enter email"
                name="email"
              />
            </div>
            <div class="mb-3">
              <label for="pwd">Password:</label>
              <input
                type="password"
                class="form-control"
                id="pwd"
                placeholder="Enter password"
                name="pswd"
              />
            </div>
            <div class="form-check mb-3">
              <label class="form-check-label">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="remember"
                />
                Remember me
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>

    <script>
      function toggleForm(formType) {
        var formElement = document.querySelector(".form-" + formType);
        formElement.classList.toggle("active");
      }
    </script>
  </body>

  =======
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <title>Đăng Nhập</title>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="stylesheet" href="phanquyen.css" />
      <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
      />
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
      <div class="container mt-3">
        <!--Form người dùng-->
        <div class="form-customer" onclick="toggleForm('customer')">
          <h2>Khách hàng</h2>
          <div class="customer">
            <form action="/action_page.php">
              <div class="mb-3 mt-3">
                <label for="email">Email:</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="Enter email"
                  name="email"
                />
              </div>
              <div class="mb-3">
                <label for="pwd">Password:</label>
                <input
                  type="password"
                  class="form-control"
                  id="pwd"
                  placeholder="Enter password"
                  name="pswd"
                />
              </div>
              <div class="form-check mb-3">
                <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                  />
                  Remember me
                </label>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>

        <!--Form Quản Lý-->
        <div class="form-admin" onclick="toggleForm('admin')">
          <h2>Quản Lý</h2>
          <div class="admin">
            <form action="/action_page.php">
              <div class="mb-3 mt-3">
                <label for="email">Email:</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="Enter email"
                  name="email"
                />
              </div>
              <div class="mb-3">
                <label for="pwd">Password:</label>
                <input
                  type="password"
                  class="form-control"
                  id="pwd"
                  placeholder="Enter password"
                  name="pswd"
                />
              </div>
              <div class="form-check mb-3">
                <label class="form-check-label">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                  />
                  Remember me
                </label>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>

      <script>
        function toggleForm(formType) {
          var formElement = document.querySelector(".form-" + formType);
          formElement.classList.toggle("active");
        }
      </script>
    </body>
  </html>
</html>
