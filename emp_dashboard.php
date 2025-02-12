<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT firstName, lastName, emailAddress, address, gender, mobileNumber, relationship_status FROM tbl_employee WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Fetch Career History
$query = "SELECT jobTitle, companyName, started, ended, still_in_role, description FROM tbl_careerhistory WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$careerHistory = $stmt->get_result();
$stmt->close();

// Fetch Certifications
$query = "SELECT licenceName, issuingOrganization, issueDate, expiryDate, description FROM tbl_certification WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certifications = $stmt->get_result();
$stmt->close();

// Fetch Educational Background
$query = "SELECT course, institution, finished, course_highlights FROM tbl_educback WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$education = $stmt->get_result();
$stmt->close();

// Fetch Languages
$query = "SELECT language FROM tbl_language WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$languages = $stmt->get_result();
$stmt->close();

// Fetch Resume
$query = "SELECT resumeFile FROM tbl_resume WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resume = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch Skills
$query = "SELECT skillName FROM tbl_skills WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
    <p>Email: <?php echo htmlspecialchars($employee['emailAddress']); ?></p>
    <p>Address: <?php echo htmlspecialchars($employee['address']); ?></p>
    <p>Gender: <?php echo htmlspecialchars($employee['gender']); ?></p>
    <p>Mobile Number: <?php echo htmlspecialchars($employee['mobileNumber']); ?></p>
    <p>Relationship Status: <?php echo htmlspecialchars($employee['relationship_status']); ?></p>
    
    <h3>Career History</h3>
    <ul>
        <?php while ($row = $careerHistory->fetch_assoc()) { ?>
            <li><?php echo htmlspecialchars($row['jobTitle'] . ' at ' . $row['companyName']); ?></li>
        <?php } ?>
    </ul>

    <h3>Certifications</h3>
    <ul>
        <?php while ($row = $certifications->fetch_assoc()) { ?>
            <li><?php echo htmlspecialchars($row['licenceName'] . ' from ' . $row['issuingOrganization']); ?></li>
        <?php } ?>
    </ul>

    <h3>Educational Background</h3>
    <ul>
        <?php while ($row = $education->fetch_assoc()) { ?>
            <li><?php echo htmlspecialchars($row['course'] . ' at ' . $row['institution']); ?></li>
        <?php } ?>
    </ul>

    <h3>Languages</h3>
    <ul>
        <?php while ($row = $languages->fetch_assoc()) { ?>
            <li><?php echo htmlspecialchars($row['language']); ?></li>
        <?php } ?>
    </ul>

    <h3>Resume</h3>
    <?php if ($resume) { ?>
        <a href="uploads/<?php echo htmlspecialchars($resume['resumeFile']); ?>" download>Download Resume</a>
    <?php } else { ?>
        <p>No resume uploaded.</p>
    <?php } ?>

    <h3>Skills</h3>
    <ul>
        <?php while ($row = $skills->fetch_assoc()) { ?>
            <li><?php echo htmlspecialchars($row['skillName']); ?></li>
        <?php } ?>
    </ul>

    <a href="includes/logout.php">Logout</a>
</body>
</html>
