<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_login.php");
    die();
}

include '../includes/emp_fetch_profile.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../fortest/style2/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a, .action-buttons button {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Adjust font size to match edit buttons */
        }
        .action-buttons button {
            background-color: #dc3545;
        }
        .action-buttons a:hover, .action-buttons button:hover {
            opacity: 0.8;
        }
        .timeline-card {
            margin-bottom: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #editModal, #passwordModal, #messageModal {
            z-index: 2; /* Ensure these modals appear on top */
        }
        .job-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            cursor: pointer;
        }
        .job-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff; /* Job title color */
        }
        .company-name {
            font-size: 1em;
            color: #6c757d; /* Company name color */
        }
        .job-details {
            margin-top: 10px;
        }
        .selected-job {
            background-color: #e9ecef;
        }
        .job-list-container {
            max-height: 80vh;
            overflow-y: auto;
        }
        @media (max-width: 767.98px) {
            .job-list-container {
                max-height: 80vh;
            }
        }
    </style>
    <script>
        function openModal(category, data = {}) {
            document.getElementById('editCategory').value = category;
            document.querySelectorAll('.modal-fields').forEach(div => div.style.display = 'none');
            document.getElementById(category + 'Fields').style.display = 'block';
            for (const key in data) {
                if (data.hasOwnProperty(key)) {
                    if (key === 'still_in_role') {
                        document.getElementById(key).checked = data[key] === 1;
                    } else {
                        document.getElementById(key).value = data[key];
                    }
                }
            }
            document.getElementById('editModal').style.display = 'block';
        }

        function openAddModal(category) {
            document.getElementById('editCategory').value = category;
            document.querySelectorAll('.modal-fields').forEach(div => div.style.display = 'none');
            document.getElementById(category + 'Fields').style.display = 'block';
            document.querySelectorAll('.modal-fields input, .modal-fields textarea').forEach(input => input.value = '');
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

        function openCareerHistoryListModal() {
            document.getElementById('careerHistoryListModal').style.display = 'block';
        }

        function closeCareerHistoryListModal() {
            document.getElementById('careerHistoryListModal').style.display = 'none';
        }

        function openCareerHistoryEditModal(data) {
            closeCareerHistoryListModal();
            openModal('careerhistory', data);
        }

        function openLanguagesListModal() {
            document.getElementById('languagesListModal').style.display = 'block';
        }

        function closeLanguagesListModal() {
            document.getElementById('languagesListModal').style.display = 'none';
        }

        function removeLanguage(id) {
            if (confirm('Are you sure you want to remove this language?')) {
                // Implement the removal logic here, e.g., send an AJAX request to the server to remove the language
            }
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
    <header class="d-print-none">
        <div class="container text-center text-lg-left">
            <div class="py-3 clearfix">
                <h1 class="site-title mb-0"><?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h1>
                <div class="site-nav">
                    <nav role="navigation">
                        <ul class="nav justify-content-center">
                            <li class="nav-item"><a class="nav-link" href="https://twitter.com/" title="Twitter"><i class="fab fa-twitter"></i><span class="menu-title sr-only">Twitter</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="https://www.facebook.com/" title="Facebook"><i class="fab fa-facebook"></i><span class="menu-title sr-only">Facebook</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="https://www.instagram.com/" title="Instagram"><i class="fab fa-instagram"></i><span class="menu-title sr-only">Instagram</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="https://github.com/" title="Github"><i class="fab fa-github"></i><span class="menu-title sr-only">Github</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="../index.php" title="Github"><i class="fab fa-github"></i><span class="menu-title sr-only">Home</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="page-content">
        <div class="container">
            <div class="cover shadow-lg bg-white">
                <div class="cover-bg p-3 p-lg-4 text-white">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="avatar hover-effect bg-white shadow-sm p-1"><img src="../fortest/images/person_1.jpg" width="200" height="200"/></div>
                        </div>
                        <div class="col-lg-8 col-md-7 text-center text-md-start">
                            <h2 class="h1 mt-2" data-aos="fade-left" data-aos-delay="0"><?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
                            <p data-aos="fade-left" data-aos-delay="100">Employee Dashboard</p>
                            <div class="d-print-none" data-aos="fade-left" data-aos-delay="200"><a class="btn btn-light text-dark shadow-sm mt-1 me-1" href="../db/pdf/emp_cv/<?php echo htmlspecialchars($cvs[0]['cv_file_name']); ?>" target="_blank">Download CV</a><a class="btn btn-success shadow-sm mt-1" href="#contact">Contact</a></div>
                        </div>
                    </div>
                </div>
                <div class="about-section pt-4 px-3 px-lg-4 mt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="h3 mb-3">About Me</h2>
                            <p>Hello! I’m <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?>. I am passionate about my work and always strive to improve my skills.</p>
                        </div>
                        <div class="col-md-5 offset-md-1">
                            <div class="row mt-2">
                                <div class="col-sm-4">
                                    <div class="pb-1">Email</div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pb-1 text-secondary"><?php echo htmlspecialchars($employee['emailAddress']); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="pb-1">Phone</div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pb-1 text-secondary"><?php echo htmlspecialchars($employee['mobileNumber']); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="pb-1">Address</div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pb-1 text-secondary"><?php echo htmlspecialchars($employee['address']); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="pb-1">Gender</div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pb-1 text-secondary"><?php echo htmlspecialchars($employee['gender']); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="pb-1">Civil Status</div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pb-1 text-secondary"><?php echo htmlspecialchars($employee['relationship_status']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="work-experience-section px-3 px-lg-4">
                    <h2 class="h3 mb-4">Career History <button class="btn btn-primary" onclick="openCareerHistoryListModal()">Edit</button></h2>
                    <div class="timeline">
                        <?php foreach ($career_history as $job): ?>
                        <div class="timeline-card timeline-card-primary card shadow-sm">
                            <div class="card-body">
                                <div class="h5 mb-1"><?php echo htmlspecialchars($job['job_title']); ?> 
                                    <span class="text-muted h6">at <?php echo htmlspecialchars($job['company_name']); ?></span>
                                </div>
                                <?php if ($job['still_in_role']): ?>
                                        <span style="color: red;">Still in role</span>
                                    <?php endif; ?>
                                <div class="text-muted text-small mb-2">from <?php echo htmlspecialchars($job['start_date']); ?> to <?php echo htmlspecialchars($job['end_date']); ?></div>
                                <div><?php echo htmlspecialchars($job['description']); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="languages-section px-3 px-lg-4">
                    <h2 class="h3 mb-4">Languages <button class="btn btn-primary" onclick="openLanguagesListModal()">Edit</button></h2>
                    <div class="timeline">
                        <?php foreach ($languages as $lang): ?>
                        <div class="timeline-card timeline-card-primary card shadow-sm">
                            <div class="card-body">
                                <div class="h5 mb-1"><?php echo htmlspecialchars($lang['language_name']); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="education-section px-3 px-lg-4 pb-4">
                    <h2 class="h3 mb-4">Educational Background</h2>
                    <div class="timeline">
                        <?php foreach ($education as $edu): ?>
                        <div class="timeline-card timeline-card-success card shadow-sm">
                            <div class="card-body">
                                <div class="h5 mb-1"><?php echo htmlspecialchars($edu['course']); ?> <span class="text-muted h6">from <?php echo htmlspecialchars($edu['institution']); ?></span></div>
                                <div class="text-muted text-small mb-2"><?php echo htmlspecialchars($edu['ending_date']); ?></div>
                                <div><?php echo htmlspecialchars($edu['course_highlights']); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="contact-section px-3 px-lg-4 pb-4" id="contact">
                    <h2 class="h3 text">Certifications</h2>
                    <div class="row">
                        <?php foreach ($certifications as $cert): ?>
                        <div class="col-md-6">
                            <div class="timeline-card timeline-card-success card shadow-sm">
                                <div class="card-body">
                                    <div class="h5 mb-1"><?php echo htmlspecialchars($cert['licence_name']); ?></div>
                                    <div class="text-muted text-small mb-2"><?php echo htmlspecialchars($cert['issuing_organization']); ?></div>
                                    <div><?php echo htmlspecialchars($cert['description']); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="pt-4 pb-4 text-muted text-center d-print-none">
        <div class="container">
            <div class="my-3">
                <div class="h4"><?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></div>
                <div class="footer-nav">
                    <nav role="navigation">
                        <ul class="nav justify-content-center">
                            <li class="nav-item"><a class="nav-link" href="https://twitter.com/" title="Twitter"><i class="fab fa-twitter"></i><span class="menu-title sr-only">Twitter</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="https://www.facebook.com/" title="Facebook"><i class="fab fa-facebook"></i><span class="menu-title sr-only">Facebook</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="https://www.instagram.com/" title="Instagram"><i class="fab fa-instagram"></i><span class="menu-title sr-only">Instagram</span></a></li>
        <div style="width: 48%;">
            <h3>Curriculum Vitae</h3>
            <form action="../includes/employee/emp_cv_upload_process.php" method="POST" enctype="multipart/form-data">
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
                    <td>
                        <div class="action-buttons">
                            <a href="../db/pdf/emp_cv/<?php echo htmlspecialchars($cv['cv_file_name']); ?>" target="_blank">Preview</a>
                            <form action="../includes/employee/emp_cv_delete_process.php" method="POST" style="display:inline;">
                                <input type="hidden" name="cv_id" value="<?php echo $cv['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this CV?')">Delete</button>
                            </form>
                        </div>
                    </td>
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
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3>Edit Information</h3>
            <form method="POST" action="../includes/employee/emp_update_profile.php">
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
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Job Title:</label>
                        <input type="text" class="form-control" id="job_title" name="job_title">
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name:</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="still_in_role" name="still_in_role">
                        <label for="still_in_role" class="form-check-label">Still in Role</label>
                    </div>
                    <div class="mb-3">
                        <label for="Jdescription" class="form-label">Job Description:</label>
                        <textarea class="form-control" id="Jdescription" name="Jdescription"></textarea>
                    </div>
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
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Change Password</h3>
            <form method="POST" action="../includes/employee/emp_update_pass.php">
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

    <div id="careerHistoryListModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeCareerHistoryListModal()">&times;</span>
            <h3>Select Career History to Edit</h3>
            <div class="job-list-container">
                <div class="job-list">
                    <?php foreach ($career_history as $job): ?>
                    <div id="job-<?= $job['id'] ?>" class="job-box" onclick='openCareerHistoryEditModal(<?php echo json_encode($job); ?>)'>
                        <div class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></div>
                        <div class="company-name">at <?php echo htmlspecialchars($job['company_name']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="languagesListModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeLanguagesListModal()">&times;</span>
            <h3>Languages</h3>
            <ul class="list-group">
                <?php foreach ($languages as $lang): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($lang['language_name']); ?>
                    <button class="btn btn-danger btn-sm float-end" onclick="removeLanguage(<?php echo $lang['id']; ?>)">x</button>
                    <button class="btn btn-secondary btn-sm float-end me-2" onclick='openModal("languages", <?php echo json_encode($lang); ?>)'>✏️</button>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn btn-primary mt-3" onclick='openAddModal("languages")'>Add</button>
        </div>
    </div>

    <a href="emp_job_list.php">Job Listing</a>
    <a href="../includes/employee/emp_logout.php">Logout</a>

    <footer>
        <p>Account Created: <?php echo htmlspecialchars($account_creation_date); ?></p>
    </footer>
</body>
</html>