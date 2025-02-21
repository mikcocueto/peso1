<?php
require "../includes/db_connect.php"; // Database connection

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $country = trim($_POST['country']);
    $companyNumber = trim($_POST['companyNumber']);
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($country) || empty($companyNumber) || empty($email) || empty($password) || empty($confirm_password)) {
        $registrationMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationMessage = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $registrationMessage = "Passwords do not match.";
    } else {
        // Check if email already exists in `tbl_logincompany`
        $checkStmt = $conn->prepare("SELECT id FROM tbl_logincompany WHERE emailAddress = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $registrationMessage = "Email is already registered.";
            $checkStmt->close();
        } else {
            $checkStmt->close();

            // Generate a salt and hash the password
            $salt = bin2hex(random_bytes(16)); // Generate a random 16-character salt
            $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

            // Insert Company Details into `tbl_company`
            $stmt1 = $conn->prepare("INSERT INTO tbl_company (firstName, lastName, country, companyNumber) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("ssss", $firstName, $lastName, $country, $companyNumber);

            if ($stmt1->execute()) {
                // Get the last inserted company_id
                $company_id = $conn->insert_id;
                
                // Insert Login Credentials into `tbl_logincompany`
                $stmt2 = $conn->prepare("INSERT INTO tbl_logincompany (company_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("isss", $company_id, $email, $hashedPassword, $salt);

                if ($stmt2->execute()) {
                    $registrationMessage = "Registration successful!";
                    header("Location: comp_login.php");
                    exit();
                } else {
                    $registrationMessage = "Error inserting into login table: " . $stmt2->error;
                }
                $stmt2->close();
            } else {
                $registrationMessage = "Error inserting into company table: " . $stmt1->error;
            }
            $stmt1->close();
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
    <title>Company Register</title>
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
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/demo_1/style.css">
    <!-- End Layout styles -->
    <link rel="shortcut icon" href="../assets/images/peso.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                <h2 class="text-center mb-4">Register</h2>
                <form action="" method="POST">
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" name="firstName" class="form-control" placeholder="First Name" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" name="lastName" class="form-control" placeholder="Last Name" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" name="country" class="form-control" placeholder="Country" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" name="companyNumber" class="form-control" placeholder="Company Number" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
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
                    <div class="input-group">
                      <input type="password" name="password" class="form-control" placeholder="Password" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <?php if (!empty($registrationMessage)): ?>
                    <div class="form-group">
                      <div class="alert alert-danger" role="alert">
                        <?php echo $registrationMessage; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div class="form-group d-flex justify-content-center">
                    <div class="form-check form-check-flat mt-0">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" required> I agree to the terms </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary submit-btn btn-block">Register</button>
                  </div>
                  <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Already have an account?</span>
                    <a href="comp_login.php" class="text-black text-small">Login</a>
                  </div>
                </form>
              </div>
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