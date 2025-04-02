<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_login.php");
    die();
}

include '../includes/employee/emp_fetch_profile.php';
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
            padding: 10px; 
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; 
            width: 100px; 
            height: 40px; 
            text-align: center; 
            display: flex;
            align-items: center;
            justify-content: center;
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
            position: relative;
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%; 
            border-radius: 10px;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 20px;
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
            z-index: 2; 
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
            color: #007bff; 
        }
        .company-name {
            font-size: 1em;
            color: #6c757d; 
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
        .cv-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .cv-section h3 {
            margin-bottom: 20px;
        }
        .cv-section form {
            margin-bottom: 20px;
        }
        .cv-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .cv-section th, .cv-section td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .cv-section th {
            background-color: #007bff;
            color: white;
        }
        .cv-section .action-buttons {
            display: flex;
            gap: 10px;
        }
        .cv-section .action-buttons a, .cv-section .action-buttons button {
            padding: 10px; /* Adjust padding for consistent height */
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            width: 100px; 
            height: 40px; 
            text-align: center; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cv-section .action-buttons button {
            background-color: #dc3545;
        }
        .cv-section .action-buttons a:hover, .cv-section .action-buttons button:hover {
            opacity: 0.8;
        }
        .tag {
            border: 1px solid #007bff; /* Add a visible border */
            display: inline-block; /* Make tags fill horizontally */
            padding: 10px; /* Increase size by 50% */
            margin: 5px;
            font-size: 1.5em; /* Increase font size by 50% */
            background-color: #f1f1f1;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s; /* Add transition for hover effect */
        }

        .tag:hover {
            background-color: #e0f7ff; /* Change background color on hover */
            border-color: #0056b3; /* Change border color on hover */
        }

        .tag .close-btn {
            margin-left: 15px; /* Increase margin by 50% */
            color: red;
            cursor: pointer;
            font-weight: bold;
            transition: color 0.3s; /* Add transition for hover effect */
        }

        .tag .close-btn:hover {
            color: darkred; /* Change color on hover */
        }
    </style>
    <input type="hidden" id="successMessage" value="<?php echo isset($_SESSION['success_message']) ? htmlspecialchars($_SESSION['success_message']) : ''; ?>">
    <input type="hidden" id="errorMessage" value="<?php echo isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message']) : ''; ?>">
    <script src="../includes/employee/js/emp_dashboard.js"></script>
</head>
<body>
    <header class="d-print-none">
        <div class="container text-center text-lg-left">
            <div class="py-3 clearfix">

            <!-- NAVBAR
                <h1 class="site-title mb-0"><?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h1>-->
            </div>
        </div>
    </header>
    <div class="page-content">
        <div class="container">
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
                <div class="cover-bg p-3 p-lg-4 text-black">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="avatar hover-effect bg-white shadow-sm p-1"><img src="../fortest/images/person_1.jpg" width="200" height="200"/></div>
                        </div>
                        <div class="col-lg-8 col-md-7 text-center text-md-start">
                            <h2 class="h1 mt-2" data-aos="fade-left" data-aos-delay="0"><?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?></h2>
                            <p data-aos="fade-left" data-aos-delay="100">Philippines, Region IV-A</p>
                            <div class="d-print-none" data-aos="fade-left" data-aos-delay="200"><a class="btn btn-light text-dark shadow-sm mt-1 me-1" href="../db/pdf/emp_cv/<?php echo htmlspecialchars($cvs[0]['cv_file_name']); ?>" target="_blank">Download CV</a><a class="btn btn-success shadow-sm mt-1" href="#contact">Contact</a></div>
                        </div>
                    </div>
                </div>
                <div class="about-section pt-4 px-3 px-lg-4 mt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="h3 mb-3">About Me 
                                <button class="btn btn-primary" onclick="openModal('personal', {
                                    firstName: '<?php echo htmlspecialchars($employee['firstName']); ?>',
                                    lastName: '<?php echo htmlspecialchars($employee['lastName']); ?>',
                                    address: '<?php echo htmlspecialchars($employee['address']); ?>',
                                    emailAddress: '<?php echo htmlspecialchars($employee['emailAddress']); ?>',
                                    gender: '<?php echo htmlspecialchars($employee['gender']); ?>',
                                    mobileNumber: '<?php echo htmlspecialchars($employee['mobileNumber']); ?>'
                                })">Edit</button>
                            </h2>
                            <p>Hello! I'm <?php echo htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']); ?>. I am passionate about my work and always strive to improve my skills.</p>
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
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="work-experience-section px-3 px-lg-4">
                    <h2 class="h3 mb-4">Career History 
                        <button class="btn btn-primary" onclick="openCareerHistoryListModal()">Edit</button>
                    </h2>
                    <div class="row">
                        <?php foreach ($career_history as $job): ?>
                        <div class="col-md-6">
                            <div class="timeline-card timeline-card-primary card shadow-sm">
                                <div class="card-body">
                                    <div class="h5 mb-1"><?php echo htmlspecialchars($job['job_title']); ?> 
                                        <span class="text-muted h6">at <?php echo htmlspecialchars($job['company_name']); ?></span>
                                    </div>
                                    <div class="text-muted text-small mb-2">
                                        <?php if ($job['still_in_role']): ?>
                                            <span style="color: red;">Still in role</span>
                                        <?php else: ?>
                                            <span style="color: transparent;">Still in role</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted text-small mb-2">
                                        from <?php echo htmlspecialchars($job['start_date'] === '0000-00-00' ? 'unspecified' : $job['start_date']); ?> 
                                        to <?php echo htmlspecialchars($job['end_date'] === '0000-00-00' ? 'unspecified' : $job['end_date']); ?>
                                    </div>
                                    <div><?php echo htmlspecialchars($job['JDescription']); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="languages-section px-3 px-lg-4">
                    <h2 class="h3 mb-4">Languages <button class="btn btn-primary" onclick="openLanguagesListModal()">Edit</button></h2>
                    <div id="tag-container">
                        <?php foreach ($languages as $lang): ?>
                        <div class="tag">
                            <?php echo htmlspecialchars($lang['language_name']); ?>
                            <span class="close-btn" onclick="removeLanguage(<?php echo $lang['id']; ?>)">×</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="skills-section px-3 px-lg-4">
                    <h2 class="h3 mb-4">Skills <button class="btn btn-primary" onclick="openSkillsListModal()">Edit</button></h2>
                    <div id="skills-container">
                        <?php foreach ($skills as $skill): ?>
                        <div class="tag">
                            <?php echo htmlspecialchars($skill['skill_name']); ?>
                            <span class="close-btn" onclick="removeSkill(<?php echo $skill['id']; ?>)">×</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="education-section px-3 px-lg-4 pb-4">
                    <h2 class="h3 mb-4">Educational Background 
                        <button class="btn btn-primary" onclick="openEducationListModal()">Edit</button>
                    </h2>
                    <div class="row">
                        <?php foreach ($education as $edu): ?>
                        <div class="col-md-6">
                            <div class="timeline-card timeline-card-success card shadow-sm">
                                <div class="card-body">
                                    <div class="h5 mb-1"><?php echo htmlspecialchars($edu['course']); ?> 
                                        <span class="text-muted h6">from <?php echo htmlspecialchars($edu['institution']); ?></span>
                                    </div>
                                    <div class="text-muted text-small mb-2"><?php echo htmlspecialchars($edu['ending_date']); ?></div>
                                    <div><?php echo htmlspecialchars($edu['course_highlights']); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr class="d-print-none"/>
                <div class="contact-section px-3 px-lg-4 pb-4" id="contact">
                    <h2 class="h3 text">Certifications <button class="btn btn-primary" onclick="openCertificationsListModal()">Edit</button></h2>
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
                <hr class="d-print-none"/>
                <div class="cv-section px-3 px-lg-4 pb-4">
                    <h2 class="h3 mb-4">Curriculum Vitae 
                        <button class="btn btn-primary" onclick="openCvUploadModal()">Upload</button>
                        <button class="btn btn-secondary" onclick="openCvListModal()">Edit Names</button>
                    </h2>
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>CV Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cvs as $cv): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cv['cv_name']); ?></td>
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
                        </tbody>
                    </table>
                </div>
                <div id="cvUploadModal" class="modal">
                    <div class="modal-content">
                        <span class="close-button" onclick="closeCvUploadModal()">&times;</span>
                        <h3>Upload Curriculum Vitae</h3>
                        <form action="../includes/employee/emp_cv_upload_process.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="cv_name" class="form-label">CV Name:</label>
                                <input type="text" class="form-control" name="cv_name" id="cv_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="cv_file" class="form-label">Upload CV (PDF only):</label>
                                <input type="file" class="form-control" name="cv_file" id="cv_file" accept="application/pdf" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Upload</button>
                        </form>
                    </div>
                </div>
                <div id="cvListModal" class="modal">
                    <div class="modal-content">
                        <span class="close-button" onclick="closeCvListModal()">&times;</span>
                        <h3>Edit CV Names</h3>
                        <ul class="list-group">
                            <?php foreach ($cvs as $cv): ?>
                            <li class="list-group-item">
                                <form action="../includes/employee/emp_cv_edit_process.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="cv_id" value="<?php echo $cv['id']; ?>">
                                    <div class="mb-3">
                                        <label for="cv_name_<?php echo $cv['id']; ?>" class="form-label">CV Name:</label>
                                        <input type="text" class="form-control" name="cv_name" id="cv_name_<?php echo $cv['id']; ?>" value="<?php echo htmlspecialchars($cv['cv_name']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                                </form>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <hr class="d-print-none"/>
            </div>
        </div>
    </div>
    <footer class="pt-4 pb-4 text-muted text-center d-print-none">
        <div class="container">
            <div class="my-3">


    <button class="edit-button" onclick="openPasswordModal()">Change Password</button>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3>Edit Information</h3>
            <form method="POST" action="../includes/employee/emp_update_profile.php">
                <input type="hidden" id="editCategory" name="category">
                <input type="hidden" id="id" name="id">
                <div id="personalFields" class="modal-fields">
                    <div class="mb-3">
                        <label for="personal_firstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="personal_firstName" name="firstName">
                    </div>
                    <div class="mb-3">
                        <label for="personal_lastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="personal_lastName" name="lastName">
                    </div>
                    <div class="mb-3">
                        <label for="personal_emailAddress" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="personal_emailAddress" name="emailAddress">
                    </div>
                    <div class="mb-3">
                        <label for="personal_address" class="form-label">Address:</label>
                        <input type="text" class="form-control" id="personal_address" name="address">
                    </div>
                    <div class="mb-3">
                        <label for="personal_gender" class="form-label">Gender:</label>
                        <input type="text" class="form-control" id="personal_gender" name="gender">
                    </div>
                    <div class="mb-3">
                        <label for="personal_mobileNumber" class="form-label">Mobile Number:</label>
                        <input type="text" class="form-control" id="personal_mobileNumber" name="mobileNumber">
                    </div>
                </div>
                <div id="careerhistoryFields" class="modal-fields" style="display:none;">
                    <div class="mb-3">
                        <label for="careerhistory_job_title" class="form-label">Job Title:</label>
                        <input type="text" class="form-control" id="careerhistory_job_title" name="job_title">
                    </div>
                    <div class="mb-3">
                        <label for="careerhistory_company_name" class="form-label">Company Name:</label>
                        <input type="text" class="form-control" id="careerhistory_company_name" name="company_name">
                    </div>
                    <div class="mb-3">
                        <label for="careerhistory_start_date" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="careerhistory_start_date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="careerhistory_end_date" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="careerhistory_end_date" name="end_date">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="careerhistory_still_in_role" name="still_in_role">
                        <label for="careerhistory_still_in_role" class="form-check-label">Still in Role</label>
                    </div>
                    <div class="mb-3">
                        <label for="careerhistory_Jdescription" class="form-label">Job Description:</label>
                        <textarea class="form-control" id="careerhistory_Jdescription" name="Jdescription"></textarea>
                    </div>
                </div>
                <div id="educationFields" class="modal-fields" style="display:none;">
                    <div class="mb-3">
                        <label for="education_course" class="form-label">Course:</label>
                        <input type="text" class="form-control" id="education_course" name="course">
                    </div>
                    <div class="mb-3">
                        <label for="education_institution" class="form-label">Institution:</label>
                        <input type="text" class="form-control" id="education_institution" name="institution">
                    </div>
                    <div class="mb-3">
                        <label for="education_ending_date" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="education_ending_date" name="ending_date">
                    </div>
                    <div class="mb-3">
                        <label for="education_course_highlights" class="form-label">Course Highlights:</label>
                        <textarea class="form-control" id="education_course_highlights" name="course_highlights"></textarea>
                    </div>
                </div>
                <div id="languagesFields" class="modal-fields" style="display:none;">
                    <label for="language_name">Language:</label>
                    <input type="text" id="languages_language_name" name="language_name"><br>
                </div>
                <div id="skillsFields" class="modal-fields" style="display:none;">
                    <label for="skill_name">Skill:</label>
                    <input type="text" id="skills_skill_name" name="skill_name"><br>
                </div>
                <div id="certificationFields" class="modal-fields" style="display:none;">
                    <div class="mb-3">
                        <label for="certification_licence_name" class="form-label">Licence Name:</label>
                        <input type="text" class="form-control" id="certification_licence_name" name="licence_name">
                    </div>
                    <div class="mb-3">
                        <label for="certification_issuing_organization" class="form-label">Issuing Organization:</label>
                        <input type="text" class="form-control" id="certification_issuing_organization" name="issuing_organization">
                    </div>
                    <div class="mb-3">
                        <label for="certification_issue_date" class="form-label">Issue Date:</label>
                        <input type="date" class="form-control" id="certification_issue_date" name="issue_date">
                    </div>
                    <div class="mb-3">
                        <label for="certification_expiry_date" class="form-label">Expiry Date:</label>
                        <input type="date" class="form-control" id="certification_expiry_date" name="expiry_date">
                    </div>
                    <div class="mb-3">
                        <label for="certification_description" class="form-label">Description:</label>
                        <textarea class="form-control" id="certification_description" name="description"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="deleteEntry()">Delete</button>
                    <div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    </div>
                </div>
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
            <div class="tag-container">
                <div class="job-list">
                    <?php foreach ($career_history as $job): ?>
                    <div id="job-<?= $job['id'] ?>" class="job-box" onclick='openCareerHistoryEditModal(<?php echo json_encode($job); ?>)'>
                        <div class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></div>
                        <div class="company-name">at <?php echo htmlspecialchars($job['company_name']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="btn btn-success mt-3" onclick="openAddModal('careerhistory')">Add</button>
        </div>
    </div>
    <div id="tag-container"></div>
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

    <div id="skillsListModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeSkillsListModal()">&times;</span>
            <h3>Skills</h3>
            <ul class="list-group">
                <?php foreach ($skills as $skill): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($skill['skill_name']); ?>
                    <button class="btn btn-danger btn-sm float-end" onclick="removeSkill(<?php echo $skill['id']; ?>)">x</button>
                    <button class="btn btn-secondary btn-sm float-end me-2" onclick='openModal("skills", <?php echo json_encode($skill); ?>)'>✏️</button>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn btn-primary mt-3" onclick='openAddModal("skills")'>Add</button>
        </div>
    </div>

    <div id="educationListModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeEducationListModal()">&times;</span>
            <h3>Select Education to Edit</h3>
            <div class="job-list-container">
                <div class="job-list">
                    <?php foreach ($education as $edu): ?>
                    <div id="edu-<?= $edu['id'] ?>" class="job-box" onclick='openEducationEditModal(<?php echo json_encode($edu); ?>)'>
                        <div class="job-title"><?php echo htmlspecialchars($edu['course']); ?></div>
                        <div class="company-name">at <?php echo htmlspecialchars($edu['institution']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="btn btn-success mt-3" onclick="openAddModal('education')">Add</button>
        </div>
    </div>

    <div id="certificationsListModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeCertificationsListModal()">&times;</span>
            <h3>Select Certification to Edit</h3>
            <div class="job-list-container">
                <div class="job-list">
                    <?php foreach ($certifications as $cert): ?>
                    <div id="cert-<?= $cert['id'] ?>" class="job-box" onclick='openModal("certification", <?php echo json_encode($cert); ?>)'>
                        <div class="job-title"><?php echo htmlspecialchars($cert['licence_name']); ?></div>
                        <div class="company-name">from <?php echo htmlspecialchars($cert['issuing_organization']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="btn btn-success mt-3" onclick="openAddModal('certification')">Add Certification</button>
        </div>
    </div>

    <a href="emp_job_list.php">Job Listing</a>
    <a href="../includes/employee/emp_logout.php">Logout</a>

    <footer>
        <p>Account Created: <?php echo htmlspecialchars($account_creation_date); ?></p>
    </footer>
</body>
</html>