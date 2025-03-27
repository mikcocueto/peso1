Add<?php
session_start(); // Start the session
include '../includes/db_connect.php'; // Include database connection
include '../includes/nav.php'; // Include navigation bar

// Check if the company is logged in
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php"); // Redirect to login page if not logged in
    die();
}
    
$company_id = $_SESSION['company_id']; // Get the company ID from the session

// Fetch company details
$query = "SELECT firstName, lastName, companyName, country, companyNumber, comp_logo_dir, company_verified FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

// Check for success or error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="../fortest/style2/style.css">
    <style>
        .logo-img {
            width: 64px;
            height: 64px;
            object-fit: cover;
        }
    </style>
    <script>
        // Function to open the modal with pre-filled data
        function openModal(category, data = {}) {
            document.getElementById('editCategory').value = category;
            document.querySelectorAll('.modal-fields').forEach(div => div.style.display = 'none');
            document.getElementById(category + 'Fields').style.display = 'block';
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    document.getElementById(key).value = data[key];
                }
            }
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }

        // Function to close the modal
        function closeModal() {
            var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            editModal.hide();
        }

        // Function to close the message modal
        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Display success or error message if available
        window.onload = function() {
            var successMessage = "<?php echo $success_message; ?>";
            var errorMessage = "<?php echo $error_message; ?>";
            if (successMessage || errorMessage) {
                document.getElementById('messageModal').style.display = 'block';
                document.getElementById('messageContent').innerText = successMessage || errorMessage;
            }
        }
    </script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($company['firstName'] . ' ' . $company['lastName']); ?></h2>
    
    <h3>Company Information</h3>
    <div class="company-logo">
        <img src="<?php echo $company['comp_logo_dir'] ? htmlspecialchars($company['comp_logo_dir']) : 'path/to/placeholder.png'; ?>" alt="Company Logo" class="logo-img">
    </div>
    <table>
        <tr class="category-header">
            <th>Field</th>
            <th>Value</th>
            <th class="table-column-action">Action</th>
        </tr>
        <tr>
            <td>First Name</td>
            <td><?php echo htmlspecialchars($company['firstName']); ?></td>
            <td><button class="edit-button" onclick="openModal('company', {
                firstName: '<?php echo htmlspecialchars($company['firstName']); ?>',
                lastName: '<?php echo htmlspecialchars($company['lastName']); ?>',
                companyName: '<?php echo htmlspecialchars($company['companyName']); ?>',
                country: '<?php echo htmlspecialchars($company['country']); ?>',
                companyNumber: '<?php echo htmlspecialchars($company['companyNumber']); ?>'
            })">Edit</button></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><?php echo htmlspecialchars($company['lastName']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td><?php echo htmlspecialchars($company['companyName']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo htmlspecialchars($company['country']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Company Number</td>
            <td><?php echo htmlspecialchars($company['companyNumber']); ?></td>
            <td></td>
        </tr>
    </table>
    
    <!-- Modal for editing information -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../includes/company/comp_update_profile.php" enctype="multipart/form-data">
                        <input type="hidden" id="editCategory" name="category">
                        <input type="hidden" id="id" name="id">
                        <div id="companyFields" class="modal-fields">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="firstName" name="firstName">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="lastName" name="lastName">
                            </div>
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Company Name:</label>
                                <input type="text" class="form-control" id="companyName" name="companyName">
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country:</label>
                                <input type="text" class="form-control" id="country" name="country">
                            </div>
                            <div class="mb-3">
                                <label for="companyNumber" class="form-label">Company Number:</label>
                                <input type="text" class="form-control" id="companyNumber" name="companyNumber">
                            </div>
                            <div class="mb-3">
                                <label for="comp_logo" class="form-label">Company Logo:</label>
                                <input type="file" class="form-control" id="comp_logo" name="comp_logo">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span id="messageContent"></span><br>
            <button class="close-button" onclick="closeMessageModal()">Close</button>
        </div>
    </div>

    <a href="../includes/company/comp_logout.php">Logout</a><br>
    <?php if ($company['company_verified'] == 1): ?>
        <a href="comp_job_post.php" class="btn btn-primary w-100 mt-2">Post a Job</a>
    <?php else: ?>
        <button class="btn btn-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#verificationModal">Post a Job</button>
    <?php endif; ?>
    <a href="comp_posted_jobs.php" class="btn btn-primary w-100 mt-2">Posted Jobs</a>

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Verification Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are currently not verified to post jobs. Please upload your business permit for verification.</p>
                    <form action="../includes/company/comp_verification_process.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="business_permit" class="form-label">Business Permit (PDF)</label>
                            <input type="file" class="form-control" id="business_permit" name="business_permit" accept="application/pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit for Verification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fortest/js/jquery.min.js"></script>
    <script>
        // Function to open the modal with pre-filled data
        function openModal(category, data = {}) {
            document.getElementById('editCategory').value = category;
            document.querySelectorAll('.modal-fields').forEach(div => div.style.display = 'none');
            document.getElementById(category + 'Fields').style.display = 'block';
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    document.getElementById(key).value = data[key];
                }
            }
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }

        // Function to close the modal
        function closeModal() {
            var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            editModal.hide();
        }

        // Function to close the message modal
        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Display success or error message if available
        window.onload = function() {
            var successMessage = "<?php echo $success_message; ?>";
            var errorMessage = "<?php echo $error_message; ?>";
            if (successMessage || errorMessage) {
                document.getElementById('messageModal').style.display = 'block';
                document.getElementById('messageContent').innerText = successMessage || errorMessage;
            }
        }
    </script>
</body>
</html>
