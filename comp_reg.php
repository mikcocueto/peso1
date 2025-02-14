<?php require "includes/db_connect.php"; ?>
<?php require "includes/nav.php"; ?>

<?php
// FORM HANDLING
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $country = trim($_POST['country']);
    $companyNumber = trim($_POST['companyNumber']);
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($country) || empty($companyNumber) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // PASSWORD HASHING WITH SALTING
        $salt = bin2hex(random_bytes(16)); // Generate a 16-byte salt
        $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

        // STEP 1: INSERT COMPANY DETAILS INTO `tbl_company`
        $stmt1 = $conn->prepare("INSERT INTO tbl_company (firstName, lastName, country, companyNumber) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssss", $firstName, $lastName, $country, $companyNumber);

        if ($stmt1->execute()) {
            // Get the last inserted company_id
            $company_id = $conn->insert_id;

            // STEP 2: INSERT LOGIN CREDENTIALS INTO `tbl_logincompany`
            $stmt2 = $conn->prepare("INSERT INTO tbl_logincompany (company_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("isss", $company_id, $email, $hashedPassword, $salt);

            if ($stmt2->execute()) {
                echo "Company registration successful!";
            } else {
                echo "Error inserting into login table: " . $stmt2->error;
            }
            $stmt2->close();
        } else {
            echo "Error inserting into company table: " . $stmt1->error;
        }

        $stmt1->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        form { background: white; padding: 20px; border-radius: 8px; width: 300px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
    </style>
</head>
<body>
    <h2>Company Registration</h2>
    <form method="POST" action="">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="text" name="country" placeholder="Country" required>
        <input type="text" name="companyNumber" placeholder="Company Number" required>
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
