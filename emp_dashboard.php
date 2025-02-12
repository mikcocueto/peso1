<?php
session_start();
require "includes/db_connect.php"; // Ensure database connection is included

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details from `tbl_employee`
$stmt = $conn->prepare("SELECT firstName, lastName, emailAddress, country, companyNumber FROM tbl_employee WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        .container { background: white; padding: 20px; border-radius: 8px; width: 400px; margin: auto; }
        .btn { display: inline-block; padding: 10px 15px; margin-top: 10px; text-decoration: none; background: #007BFF; color: white; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['firstName']); ?>!</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['emailAddress']); ?></p>
        
        <h3>Your Information</h3>
        <table>
            <tr><th>First Name:</th><td><?php echo htmlspecialchars($user['firstName']); ?></td></tr>
            <tr><th>Last Name:</th><td><?php echo htmlspecialchars($user['lastName']); ?></td></tr>
            <tr><th>Country:</th><td><?php echo htmlspecialchars($user['country']); ?></td></tr>
            <tr><th>Company Number:</th><td><?php echo htmlspecialchars($user['companyNumber']); ?></td></tr>
        </table>

        <br>
        <a href="includes/logout.php" class="btn" style="background: red;">Logout</a>
    </div>

</body>
</html>
