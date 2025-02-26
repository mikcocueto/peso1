<?php
session_start();
include '../includes/db_connect.php';
include '../includes/nav.php';

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
$query = "SELECT id, course, institution, ending_date, course_highlights FROM tbl_educback WHERE user_id = ?";
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

// Fetch certifications
$query = "SELECT id, licence_name, issuing_organization, issue_date, expiry_date, description FROM tbl_certification WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch account creation date
$query = "SELECT create_timestamp FROM tbl_employee WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$account_creation_date = $result->fetch_assoc()['create_timestamp'];
$stmt->close();

// Fetch CVs
$query = "SELECT id, cv_file_name, cv_dir FROM tbl_emp_cv WHERE emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cvs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check for success or error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
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
        function openPasswordModal() {
            document.getElementById('passwordModal').style.display = 'block';
        }
        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }
        window.onload = function() {
            var successMessage = "<?php echo isset($_SESSION['success_message']) ? $_SESSION['success_message'] : ''; ?>";
            var errorMessage = "<?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?>";
            if (successMessage || errorMessage) {
                document.getElementById('messageModal').style.display = 'block';
                document.getElementById('messageContent').innerText = successMessage || errorMessage;
                <?php unset($_SESSION['success_message'], $_SESSION['error_message']); ?>
            }
        }
    </script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
    
    <h3>Personal Information</h3>
    <table>
        <tr class="category-header">
            <th>Field</th>
            <th>Value</th>
            <th class="table-column-action">Action</th>
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
            <th class="table-column-action">Action</th>
        </tr>
        <?php foreach ($career_history as $index => $job): ?>
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
                Jdescription: '<?php echo htmlspecialchars($job['description']); ?>'
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
            <th class="table-column-action">Action</th>
        </tr>
        <?php foreach ($education as $edu): ?>
        <tr>
            <td><?php echo htmlspecialchars($edu['course']); ?></td>
            <td><?php echo htmlspecialchars($edu['institution']); ?></td>
            <td><?php echo htmlspecialchars($edu['ending_date']); ?></td>
            <td><?php echo htmlspecialchars($edu['course_highlights']); ?></td>
            <td><button class="edit-button" onclick="openModal('education', {
                id: '<?php echo $edu['id']; ?>',
                course: '<?php echo htmlspecialchars($edu['course']); ?>',
                institution: '<?php echo htmlspecialchars($edu['institution']); ?>',
                ending_date: '<?php echo htmlspecialchars($edu['ending_date']); ?>',
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
            <th class="table-column-action">Action</th>
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
    
    <h3>Certifications</h3>
    <table>
        <tr class="category-header">
            <th>Licence Name</th>
            <th>Issuing Organization</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Description</th>
            <th class="table-column-action">Action</th>
        </tr>
        <?php foreach ($certifications as $cert): ?>
        <tr>
            <td><?php echo htmlspecialchars($cert['licence_name']); ?></td>
            <td><?php echo htmlspecialchars($cert['issuing_organization']); ?></td>
            <td><?php echo htmlspecialchars($cert['issue_date']); ?></td>
            <td><?php echo htmlspecialchars($cert['expiry_date']); ?></td>
            <td><?php echo htmlspecialchars($cert['description']); ?></td>
            <td><button class="edit-button" onclick="openModal('certification', {
                id: '<?php echo $cert['id']; ?>',
                licence_name: '<?php echo htmlspecialchars($cert['licence_name']); ?>',
                issuing_organization: '<?php echo htmlspecialchars($cert['issuing_organization']); ?>',
                issue_date: '<?php echo htmlspecialchars($cert['issue_date']); ?>',
                expiry_date: '<?php echo htmlspecialchars($cert['expiry_date']); ?>',
                description: '<?php echo htmlspecialchars($cert['description']); ?>'
            })">Edit</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <button class="edit-button" onclick="openModal('certification')">Add Certification</button>

    <div style="display: flex; justify-content: space-between;">
        <div style="width: 48%;">
            <h3>Curriculum Vitae</h3>
            <form action="../includes/emp_cv_upload_process.php" method="POST" enctype="multipart/form-data">
                <label for="cv_file">Upload CV (PDF only):</label>
                <input type="file" name="cv_file" id="cv_file" accept="application/pdf" required>
                <button type="submit">Upload</button>
            </form>
            <table>
                <tr class="category-header">
                    <th>File Name</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cvs as $cv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cv['cv_file_name']); ?></td>
                    <td><a href="../db/pdf/emp_cv/<?php echo htmlspecialchars($cv['cv_file_name']); ?>" target="_blank">Preview</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div style="width: 48%;">
            <h3>Certifications</h3>
            <!-- ...existing code for certifications... -->
        </div>
    </div>

    <button class="edit-button" onclick="openPasswordModal()">Change Password</button>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Information</h3>
            <form method="POST" action="../includes/emp_update_profile.php">
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
                    <label for="Jdescription">Job Description:</label>
                    <textarea id="Jdescription" name="Jdescription"></textarea><br>
                </div>
                <div id="educationFields" class="modal-fields" style="display:none;">
                    <label for="course">Course:</label>
                    <input type="text" id="course" name="course"><br>
                    <label for="institution">Institution:</label>
                    <input type="text" id="institution" name="institution"><br>
                    <label for="ending_date">End Date:</label>
                    <input type="date" id="ending_date" name="ending_date"><br>
                    <label for="course_highlights">Course Highlights:</label>
                    <input type="text" id="course_highlights" name="course_highlights"><br>
                </div>
                <div id="languagesFields" class="modal-fields" style="display:none;">
                    <label for="language_name">Language:</label>
                    <input type="text" id="language_name" name="language_name"><br>
                </div>
                <div id="certificationFields" class="modal-fields" style="display:none;">
                    <label for="licence_name">Licence Name:</label>
                    <input type="text" id="licence_name" name="licence_name"><br>
                    <label for="issuing_organization">Issuing Organization:</label>
                    <input type="text" id="issuing_organization" name="issuing_organization"><br>
                    <label for="issue_date">Issue Date:</label>
                    <input type="date" id="issue_date" name="issue_date"><br>
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="date" id="expiry_date" name="expiry_date"><br>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea><br>
                </div>
                <button type="submit">Save</button>
                <button type="button" class="close-button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Change Password</h3>
            <form method="POST" action="../includes/emp_update_pass.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <label for="oldPassword">Old Password:</label>
                <input type="password" id="oldPassword" name="oldPassword" required><br>
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required><br>
                <label for="confirmPassword">Confirm New Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required><br>
                <button type="submit">Change Password</button>
                <button type="button" class="close-button" onclick="closePasswordModal()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span id="messageContent"></span><br>
            <button class="close-button" onclick="closeMessageModal()">Close</button>
        </div>
    </div>

    <a href="../includes/emp_logout.php">Logout</a>

    <footer>
        <p>Account Created: <?php echo htmlspecialchars($account_creation_date); ?></p>
    </footer>
</body>
</html>