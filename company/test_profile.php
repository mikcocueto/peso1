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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* Info Section Styling */
#infoSection {
  margin: 20px;
}

.info-card {
  display: grid;
  gap: 15px;
  background-color: #ffffff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.info-row {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 10px;
  font-size: 1rem;
}

.info-row span {
  display: block;
}

.fw-semibold {
  font-weight: 600;
  color: #333;
}

/* Edit Form Styling */
.edit-form {
  display: grid;
  gap: 20px;
  margin-top: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: 600;
  color: #333;
}

.form-control {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  width: 100%;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

.btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: #0056b3;
}

</style>
<body class="bg-light">
<?php include 'comp_navbar&tab.php'; ?>

    <!-- Page Content -->
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
                        <button id="editButton" type="button" class="btn btn-primary">Edit</button>
                    </div>

                    <!-- Info Display -->
                    <div id="infoSection" class="mb-4">
                        <table class="table">
                            <tbody>
                                <tr><td class="fw-semibold">First Name:</td><td><?php echo htmlspecialchars($company['firstName']); ?></td></tr>
                                <tr><td class="fw-semibold">Last Name:</td><td><?php echo htmlspecialchars($company['lastName']); ?></td></tr>
                                <tr><td class="fw-semibold">Company Name:</td><td><?php echo htmlspecialchars($company['companyName']); ?></td></tr>
                                <tr><td class="fw-semibold">Country:</td><td><?php echo htmlspecialchars($company['country']); ?></td></tr>
                                <tr><td class="fw-semibold">Company Number:</td><td><?php echo htmlspecialchars($company['companyNumber']); ?></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Edit Form -->
                    <form id="editForm" method="POST" action="includes/company/update_company_info.php" enctype="multipart/form-data" class="d-none">
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
                                <tr><td class="fw-semibold"><label for="companyLogo">Company Logo</label></td>
                                    <td><input type="file" id="companyLogo" name="companyLogo" class="form-control"></td></tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" id="cancelButton" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');
        const infoSection = document.getElementById('infoSection');
        const editForm = document.getElementById('editForm');

        editButton.addEventListener('click', () => {
            infoSection.classList.add('d-none');
            editForm.classList.remove('d-none');
            editButton.classList.add('d-none'); // Hide the edit button
        });

        cancelButton.addEventListener('click', () => {
            editForm.classList.add('d-none');
            infoSection.classList.remove('d-none');
            editButton.classList.remove('d-none'); // Show the edit button
        });

        // Prevent Enter key from submitting the form
        editForm.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });

        // Preview uploaded logo
        document.getElementById('companyLogo').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('logoPreview').src = reader.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</body>
</html>
