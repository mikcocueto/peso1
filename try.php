<?php
session_start(); // Start the session
include 'includes/db_connect.php'; // Include database connection

// Check if the company is logged in
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php"); // Redirect to login page if not logged in
    die();
}
    
$company_id = $_SESSION['company_id']; // Get the company ID from the session

// Fetch company details
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-light">
    <?php include 'company/comp_navbar&tab.php'; ?> 
    <main class="p-4 d-flex gap-4">
        <div class="bg-white p-3 border rounded shadow-sm w-25">
            <img src="<?php echo $company['comp_logo_dir'] ? htmlspecialchars($company['comp_logo_dir']) : 'path/to/placeholder.png'; ?>" alt="Company Logo" class="d-block mx-auto mb-3" width="100" height="100">
            <p class="text-center fw-semibold">Welcome, <?php echo htmlspecialchars($company['firstName'] . ' ' . $company['lastName']); ?></p>
        </div>
        <div class="bg-white p-3 border rounded shadow-sm w-75">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 fw-bold">Company Information</h3>
            </div>
            <div class="mb-2">
                <p><span class="fw-semibold">First Name:</span> <?php echo htmlspecialchars($company['firstName']); ?></p>
                <p><span class="fw-semibold">Last Name:</span> <?php echo htmlspecialchars($company['lastName']); ?></p>
                <p><span class="fw-semibold">Company Name:</span> <?php echo htmlspecialchars($company['companyName']); ?></p>
                <p><span class="fw-semibold">Country:</span> <?php echo htmlspecialchars($company['country']); ?></p>
                <p><span class="fw-semibold">Company Number:</span> <?php echo htmlspecialchars($company['companyNumber']); ?></p>
            </div>
        </div>
    </main>
    <footer class="p-3 d-flex justify-content-end">
        <form method="POST" action="includes/company/comp_logout.php">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
