<?php
require "../includes/db_connect.php"; // Database connection

$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    // Basic validation
    if (empty($email) || empty($new_password) || empty($confirm_new_password)) {
        $updateMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateMessage = "Invalid email format.";
    } elseif ($new_password !== $confirm_new_password) {
        $updateMessage = "Passwords do not match.";
    } else {
        // Check if email exists in `tbl_comp_login`
        $checkStmt = $conn->prepare("SELECT id FROM tbl_comp_login WHERE emailAddress = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();

            // Generate a new salt and hash the new password
            $salt = bin2hex(random_bytes(16)); // Generate a random 16-character salt
            $hashedPassword = password_hash($new_password . $salt, PASSWORD_BCRYPT);

            // Update the password in `tbl_comp_login`
            $stmt = $conn->prepare("UPDATE tbl_comp_login SET password = ?, salt = ? WHERE emailAddress = ?");
            $stmt->bind_param("sss", $hashedPassword, $salt, $email);

            if ($stmt->execute()) {
                $updateMessage = "Password updated successfully!";
                header("Location: comp_login.php");
                exit();
            } else {
                $updateMessage = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $updateMessage = "No account found with that email.";
            $checkStmt->close();
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Password</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
    <link rel="stylesheet" href="../assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/css/shared/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../assets/images/peso.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                <h2 class="text-center mb-4">Update Password</h2>
                <form action="" method="POST">
                  <div class="form-group">
                    <label class="label">Email Address</label>
                    <div class="input-group">
                      <input type="email" name="emailAddress" class="form-control" placeholder="Email Address" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="label">New Password</label>
                    <div class="input-group">
                      <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="label">Confirm New Password</label>
                    <div class="input-group">
                      <input type="password" name="confirm_new_password" class="form-control" placeholder="Confirm New Password" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <?php if (!empty($updateMessage)): ?>
                    <div class="form-group">
                      <div class="alert alert-danger" role="alert">
                        <?php echo $updateMessage; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div class="form-group">
                    <button class="btn btn-primary submit-btn btn-block">Update Password</button>
                  </div>
                  <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Remembered your password?</span>
                    <a href="comp_login.php" class="text-black text-small">Login</a>
                  </div>
                </form>
              </div>
              <ul class="auth-footer">
                <li>
                  <a href="#">Conditions</a>
                </li>
                <li>
                  <a href="#">Help</a>
                </li>
                <li>
                  <a href="#">Terms</a>
                </li>
              </ul>
              <p class="footer-text text-center">Copyright © 2025 Public Employment Service Office. All rights reserved.</p>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="../assets/js/shared/off-canvas.js"></script>
    <script src="../assets/js/shared/misc.js"></script>
    <!-- endinject -->
    <script src="../assets/js/shared/jquery.cookie.js" type="text/javascript"></script>
  </body>
</html>