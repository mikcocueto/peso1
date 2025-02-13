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
$query = "SELECT id, job_title, company_name, start_date, end_date, still_in_role, description FROM tbl_careerhistory WHERE user_id = ?";
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
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function openModal(category, data = {}) {
            document.getElementById('editCategory').value = category;
            document.querySelectorAll('.modal-fields').forEach(div => div.style.display = 'none');
            document.getElementById(category + 'Fields').style.display = 'block';
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    if (key === 'still_in_role') {
                        document.getElementById(key).checked = data[key] === '1';
                    } else {
                        document.getElementById(key).value = data[key];
                    }
                }
            }
            document.getElementById('editModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }
        window.onload = function() {
            var successMessage = "<?php echo $success_message; ?>";
            var errorMessage = "<?php echo $error_message; ?>";
            if (successMessage || errorMessage) {
                document.getElementById('messageModal').style.display = 'block';
                document.getElementById('messageContent').innerText = successMessage || errorMessage;
            }
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .category-header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }
        .edit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .edit-button:hover {
            background-color: #45a049;
        }
        .close-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .close-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
    
    <h3>Personal Information</h3>
    <table>
        <tr class="category-header">
            <th>Field</th>
            <th>Value</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo htmlspecialchars($employee['emailAddress']); ?></td>
            <td><button class="edit-button" onclick="openModal('personal', {
                emailAddress: '<?php echo htmlspecialchars($employee['emailAddress']); ?>',
                address: '<?php echo htmlspecialchars($employee['address']); ?>',
                gender: '<?php echo htmlspecialchars($employee['gender']); ?>',
                mobileNumber: '<?php echo htmlspecialchars($employee['mobileNumber']); ?>',
                relationship_status: '<?php echo htmlspecialchars($employee['relationship_status']); ?>'
            })">Edit</button></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><?php echo htmlspecialchars($employee['address']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><?php echo htmlspecialchars($employee['gender']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Mobile Number</td>
            <td><?php echo htmlspecialchars($employee['mobileNumber']); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Relationship Status</td>
            <td><?php echo htmlspecialchars($employee['relationship_status']); ?></td>
            <td></td>
        </tr>
    </table>
    
    <h3>Career History</h3>
    <table>
        <tr class="category-header">
            <th>Job Title</th>
            <th>Company Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Still in Role</th>
            <th>Job Description</th>
            <th>Action</th>
        </tr>
        <?php foreach ($career_history as $job): ?>
        <tr>
            <td><?php echo htmlspecialchars($job['job_title']); ?></td>
            <td><?php echo htmlspecialchars($job['company_name']); ?></td>
            <td><?php echo htmlspecialchars($job['start_date']); ?></td>
            <td><?php echo htmlspecialchars($job['end_date']); ?></td>
            <td><?php echo $job['still_in_role'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo htmlspecialchars($job['description']); ?></td>
            <td><button class="edit-button" onclick="openModal('careerhistory', {
                id: '<?php echo $job['id']; ?>',
                job_title: '<?php echo htmlspecialchars($job['job_title']); ?>',
                company_name: '<?php echo htmlspecialchars($job['company_name']); ?>',
                start_date: '<?php echo htmlspecialchars($job['start_date']); ?>',
                end_date: '<?php echo htmlspecialchars($job['end_date']); ?>',
                still_in_role: '<?php echo $job['still_in_role']; ?>',
                description: '<?php echo htmlspecialchars($job['description']); ?>'
            })">Edit</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button class="edit-button" onclick="openModal('careerhistory')">Add Career History</button>
    
    <h3>Educational Background</h3>
    <table>
        <tr class="category-header">
            <th>Course</th>
            <th>Institution</th>
            <th>End Date</th>
            <th>Course Highlights</th>
            <th>Action</th>
        </tr>
        <?php foreach ($education as $edu): ?>
        <tr>
            <td><?php echo htmlspecialchars($edu['course']); ?></td>
            <td><?php echo htmlspecialchars($edu['institution']); ?></td>
            <td><?php echo htmlspecialchars($edu['end_date']); ?></td>
            <td><?php echo htmlspecialchars($edu['course_highlights']); ?></td>
            <td><button class="edit-button" onclick="openModal('education', {
                id: '<?php echo $edu['id']; ?>',
                course: '<?php echo htmlspecialchars($edu['course']); ?>',
                institution: '<?php echo htmlspecialchars($edu['institution']); ?>',
                end_date: '<?php echo htmlspecialchars($edu['end_date']); ?>',
                course_highlights: '<?php echo htmlspecialchars($edu['course_highlights']); ?>'
            })">Edit</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button class="edit-button" onclick="openModal('education')">Add Education</button>
    
    <h3>Languages</h3>
    <table>
        <tr class="category-header">
            <th>Language</th>
            <th>Action</th>
        </tr>
        <?php foreach ($languages as $lang): ?>
        <tr>
            <td><?php echo htmlspecialchars($lang['language_name']); ?></td>
            <td><button class="edit-button" onclick="openModal('languages', {
                id: '<?php echo $lang['id']; ?>',
                language_name: '<?php echo htmlspecialchars($lang['language_name']); ?>'
            })">Edit</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button class="edit-button" onclick="openModal('languages')">Add Language</button>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Information</h3>
            <form method="POST" action="includes/emp_update_profile.php">
                <input type="hidden" id="editCategory" name="category">
                <input type="hidden" id="id" name="id">
                <div id="personalFields" class="modal-fields">
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
                <div id="careerhistoryFields" class="modal-fields" style="display:none;">
                    <label for="job_title">Job Title:</label>
                    <input type="text" id="job_title" name="job_title"><br>
                    <label for="company_name">Company Name:</label>
                    <input type="text" id="company_name" name="company_name"><br>
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date"><br>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date"><br>
                    <label for="still_in_role">Still in Role:</label>
                    <input type="checkbox" id="still_in_role" name="still_in_role"><br>
                    <label for="description">Job Description:</label>
                    <textarea id="description" name="description"></textarea><br>
                </div>
                <div id="educationFields" class="modal-fields" style="display:none;">
                    <label for="course">Course:</label>
                    <input type="text" id="course" name="course"><br>
                    <label for="institution">Institution:</label>
                    <input type="text" id="institution" name="institution"><br>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date"><br>
                    <label for="course_highlights">Course Highlights:</label>
                    <input type="text" id="course_highlights" name="course_highlights"><br>
                </div>
                <div id="languagesFields" class="modal-fields" style="display:none;">
                    <label for="language_name">Language:</label>
                    <input type="text" id="language_name" name="language_name"><br>
                </div>
                <button type="submit">Save</button>
                <button type="button" class="close-button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span id="messageContent"></span><br>
            <button class="close-button" onclick="closeMessageModal()">Close</button>
        </div>
    </div>

    <a href="includes/logout.php">Logout</a>
</body>
</html>