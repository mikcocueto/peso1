<?php
session_start();
include 'includes/db_connect.php';
include 'includes/nav.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_login.php");
    die();
}

$user_id = $_SESSION['user_id'];

// Fetch employee details
$query = "SELECT firstName, lastName, emailAddress, address, gender, mobileNumber, relationship_status FROM tbl_employee WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Fetch career history
$query = "SELECT id, job_title, company_name, start_date, end_date FROM tbl_careerhistory WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$career_history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch educational background
$query = "SELECT id, course, institution, end_date, course_highlights FROM tbl_educback WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$education = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch languages
$query = "SELECT id, language_name FROM tbl_language WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$languages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function openModal(category, data = {}) {
            document.getElementById('editCategory').value = category;
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    document.getElementById(key).value = data[key];
                }
            }
            document.getElementById('editModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
    
    <h3>Personal Information</h3>
    <p>Email: <?php echo htmlspecialchars($employee['emailAddress']); ?></p>
    <p>Address: <?php echo htmlspecialchars($employee['address']); ?></p>
    <p>Gender: <?php echo htmlspecialchars($employee['gender']); ?></p>
    <p>Mobile Number: <?php echo htmlspecialchars($employee['mobileNumber']); ?></p>
    <p>Relationship Status: <?php echo htmlspecialchars($employee['relationship_status']); ?></p>
    <button onclick="openModal('personal', {
        emailAddress: '<?php echo htmlspecialchars($employee['emailAddress']); ?>',
        address: '<?php echo htmlspecialchars($employee['address']); ?>',
        gender: '<?php echo htmlspecialchars($employee['gender']); ?>',
        mobileNumber: '<?php echo htmlspecialchars($employee['mobileNumber']); ?>',
        relationship_status: '<?php echo htmlspecialchars($employee['relationship_status']); ?>'
    })">Edit Personal Information</button>
    
    <h3>Career History</h3>
    <?php foreach ($career_history as $job): ?>
        <p><?php echo htmlspecialchars($job['job_title'] . ' at ' . $job['company_name']); ?></p>
    <?php endforeach; ?>
    <button onclick="openModal('careerhistory')">Edit Career History</button>
    
    <h3>Educational Background</h3>
    <?php foreach ($education as $edu): ?>
        <p><?php echo htmlspecialchars($edu['course'] . ' from ' . $edu['institution']); ?></p>
    <?php endforeach; ?>
    <button onclick="openModal('education')">Edit Educational Background</button>
    
    <h3>Languages</h3>
    <?php foreach ($languages as $lang): ?>
        <p><?php echo htmlspecialchars($lang['language_name']); ?></p>
    <?php endforeach; ?>
    <button onclick="openModal('languages')">Edit Languages</button>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Information</h3>
            <form method="POST" action="includes/emp_update_profile.php">
                <input type="hidden" id="editCategory" name="category">
                <div id="personalFields">
                    <label for="emailAddress">Email:</label>
                    <input type="text" id="emailAddress" name="emailAddress"><br>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address"><br>
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender"><br>
                    <label for="mobileNumber">Mobile Number:</label>
                    <input type="text" id="mobileNumber" name="mobileNumber"><br>
                    <label for="relationship_status">Relationship Status:</label>
                    <input type="text" id="relationship_status" name="relationship_status"><br>
                </div>
                <div id="careerFields" style="display:none;">
                    <!-- Add career history fields here -->
                </div>
                <div id="educationFields" style="display:none;">
                    <!-- Add educational background fields here -->
                </div>
                <div id="languageFields" style="display:none;">
                    <!-- Add language fields here -->
                </div>
                <button type="submit">Save</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <a href="includes/logout.php">Logout</a>
</body>
</html>