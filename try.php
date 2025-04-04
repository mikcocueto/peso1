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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl font-bold">PESO for Company</h1>
        </div>
        <h2 class="text-xl">Dashboard</h2>
        <div class="flex items-center space-x-4">
            <i class="fas fa-bell text-xl"></i>
            <i class="fas fa-envelope text-xl"></i>
            <i class="fas fa-user-circle text-xl"></i>
        </div>
    </header>
    <nav class="bg-gray-300 p-2 flex space-x-4">
        <a href="#" class="text-black font-semibold">Dashboard</a>
        <a href="#" class="text-black font-semibold">Jobs</a>
        <a href="#" class="text-black font-semibold">Candidates</a>
    </nav>
    <main class="p-4 flex space-x-4">
        <div class="bg-white p-4 border rounded shadow w-1/4">
            <img src="<?php echo $company['comp_logo_dir'] ? htmlspecialchars($company['comp_logo_dir']) : 'path/to/placeholder.png'; ?>" alt="Company Logo" class="mx-auto mb-4" width="100" height="100">
            <p class="text-center font-semibold">Welcome, <?php echo htmlspecialchars($company['firstName'] . ' ' . $company['lastName']); ?></p>
        </div>
        <div class="bg-white p-4 border rounded shadow w-3/4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Company Information</h3>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">First Name:</span> <?php echo htmlspecialchars($company['firstName']); ?></p>
                <p><span class="font-semibold">Last Name:</span> <?php echo htmlspecialchars($company['lastName']); ?></p>
                <p><span class="font-semibold">Company Name:</span> <?php echo htmlspecialchars($company['companyName']); ?></p>
                <p><span class="font-semibold">Country:</span> <?php echo htmlspecialchars($company['country']); ?></p>
                <p><span class="font-semibold">Company Number:</span> <?php echo htmlspecialchars($company['companyNumber']); ?></p>
            </div>
        </div>
    </main>
    <footer class="p-4 flex justify-end">
        <form method="POST" action="includes/company/comp_logout.php">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </footer>
</body>
</html>
