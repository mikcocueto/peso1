<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    die();
}

$company_id = $_SESSION['company_id'];

$query = "SELECT firstName, lastName, companyName, country, companyNumber, comp_logo_dir FROM tbl_comp_info WHERE company_id = ?";
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
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="../includes/company/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body class="bg-light">
<?php include 'comp_navbar&tab.php'; ?>
    <main class="p-4 w-100">
        <div class="row g-4 mt-4">
            <!-- Welcome Panel -->
            <div class="col-md-3">
                <div class="bg-white p-4 border rounded shadow">
                    <img id="logoPreview" src="<?php echo $company['comp_logo_dir'] ? htmlspecialchars($company['comp_logo_dir']) : 'path/to/placeholder.png'; ?>" 
                         alt="Company Logo" class="d-block mx-auto mb-4" width="200" height="200">
                    <p class="text-center fw-bold">Welcome, <?php echo htmlspecialchars($company['firstName'] . ' ' . $company['lastName']); ?></p>
                </div>
            </div>
            <!-- Info & Edit Panel -->
            <div class="col-md-9">
                <div class="bg-white p-4 border rounded shadow">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">Company Information</h3>
                        <button id="editButton" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    </div>

                    <!-- Info Display -->
                    <div id="comp_profile-infoSection" class="mb-4">
                        <table class="table">
                            <tbody>
                                <tr><td class="comp_profile-fw-semibold">First Name:</td><td><?php echo htmlspecialchars($company['firstName']); ?></td></tr>
                                <tr><td class="comp_profile-fw-semibold">Last Name:</td><td><?php echo htmlspecialchars($company['lastName']); ?></td></tr>
                                <tr><td class="comp_profile-fw-semibold">Company Name:</td><td><?php echo htmlspecialchars($company['companyName']); ?></td></tr>
                                <tr><td class="comp_profile-fw-semibold">Country:</td><td><?php echo htmlspecialchars($company['country']); ?></td></tr>
                                <tr><td class="comp_profile-fw-semibold">Company Number:</td><td><?php echo htmlspecialchars($company['companyNumber']); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Company Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editForm" method="POST" action="../includes/company/comp_update_profile.php" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="category" value="company">
                                <table class="table">
                                    <tbody>
                                        <tr><td class="fw-semibold"><label for="firstName">First Name</label></td>
                                            <td><input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($company['firstName']); ?>" class="form-control"></td></tr>
                                        <tr><td class="fw-semibold"><label for="lastName">Last Name</label></td>
                                            <td><input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($company['lastName']); ?>" class="form-control"></td></tr>
                                        <tr><td class="fw-semibold"><label for="companyName">Company Name</label></td>
                                            <td><input type="text" id="companyName" name="companyName" value="<?php echo htmlspecialchars($company['companyName']); ?>" class="form-control"></td></tr>
                                        <tr><td class="fw-semibold"><label for="country">Country</label></td>
                                            <td><input type="text" id="country" name="country" value="<?php echo htmlspecialchars($company['country']); ?>" class="form-control"></td></tr>
                                        <tr><td class="fw-semibold"><label for="companyNumber">Company Number</label></td>
                                            <td><input type="text" id="companyNumber" name="companyNumber" value="<?php echo htmlspecialchars($company['companyNumber']); ?>" class="form-control"></td></tr>
                                        <tr><td class="fw-semibold"><label for="comp_logo">Company Logo</label></td>
                                            <td><input type="file" id="comp_logo" name="comp_logo" class="form-control"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for displaying messages -->
    <div id="comp_profile-messageModal" class="modal">
        <div class="comp_profile-modal-content">
            <div class="comp_profile-modal-icon">
                <i class="fas fa-check-circle success-icon"></i>
            </div>
            <span id="comp_profile-messageContent"></span>
            <button class="comp_profile-close-button" onclick="closeMessageModal()">Close</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
    <script>
        const editButton = document.getElementById('editButton');
        const infoSection = document.getElementById('comp_profile-infoSection');
        const editForm = document.getElementById('editForm');

        // Function to close the message modal
        function closeMessageModal() {
            document.getElementById('comp_profile-messageModal').style.display = 'none';
        }

        // Display success or error message if available
        window.onload = function() {
            var successMessage = "<?php echo $success_message; ?>";
            var errorMessage = "<?php echo $error_message; ?>";
            if (successMessage || errorMessage) {
                const modal = document.getElementById('comp_profile-messageModal');
                const modalContent = modal.querySelector('.comp_profile-modal-content');
                const modalIcon = modal.querySelector('.comp_profile-modal-icon i');
                
                modal.style.display = 'flex';
                document.getElementById('comp_profile-messageContent').innerText = successMessage || errorMessage;
                
                if (successMessage) {
                    modalContent.classList.add('success');
                    modalIcon.className = 'fas fa-check-circle';
                } else {
                    modalContent.classList.add('error');
                    modalIcon.className = 'fas fa-exclamation-circle';
                }
            }
        }

        // Prevent Enter key from submitting the form
        editForm.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });

        // Preview uploaded logo
        document.getElementById('comp_logo').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('logoPreview').src = reader.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</body>
</html>
