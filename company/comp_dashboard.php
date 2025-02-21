<?php
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
$query = "SELECT firstName, lastName, companyName, country, companyNumber, comp_logo_dir FROM tbl_company WHERE company_id = ?";
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
    <link rel="stylesheet" href="style/style.css">
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
            document.getElementById('editModal').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
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
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Information</h3>
            <form method="POST" action="../includes/comp_update_profile.php" enctype="multipart/form-data">
                <input type="hidden" id="editCategory" name="category">
                <input type="hidden" id="id" name="id">
                <div id="companyFields" class="modal-fields">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName"><br>
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName"><br>
                    <label for="companyName">Company Name:</label>
                    <input type="text" id="companyName" name="companyName"><br>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country"><br>
                    <label for="companyNumber">Company Number:</label>
                    <input type="text" id="companyNumber" name="companyNumber"><br>
                    <label for="comp_logo">Company Logo:</label>
                    <input type="file" id="comp_logo" name="comp_logo"><br>
                </div>
                <button type="submit">Save</button>
                <button type="button" class="close-button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Modal for displaying messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span id="messageContent"></span><br>
            <button class="close-button" onclick="closeMessageModal()">Close</button>
        </div>
    </div>

    <a href="../includes/comp_logout.php">Logout</a><br>
    <a href="comp_job_post.php" class="btn btn-primary w-100 mt-2">Post Job</a>
</body>
</html>
