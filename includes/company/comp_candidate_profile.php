<?php
session_start();
require "../db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['application_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Application ID is required']);
    exit();
}

$application_id = $_GET['application_id'];

// Fetch candidate information
$query = "SELECT 
    ja.id as application_id,
    ja.application_time,
    ja.status,
    ei.*,
    GROUP_CONCAT(DISTINCT es.skill_name) as skills,
    GROUP_CONCAT(DISTINCT el.language_name) as languages
FROM tbl_job_application ja
JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
LEFT JOIN tbl_emp_skills es ON ei.user_id = es.user_id
LEFT JOIN tbl_emp_language el ON ei.user_id = el.user_id
WHERE ja.id = ?
GROUP BY ja.id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result->fetch_assoc();

if (!$candidate) {
    http_response_code(404);
    echo json_encode(['error' => 'Candidate not found']);
    exit();
}

// Fetch application files
$files_query = "SELECT file_inserted FROM tbl_job_application_files WHERE application_id = ?";
$stmt = $conn->prepare($files_query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$files_result = $stmt->get_result();
$files = $files_result->fetch_all(MYSQLI_ASSOC);

// Fetch education history
$edu_query = "SELECT * FROM tbl_emp_educback WHERE user_id = ? ORDER BY ending_date DESC";
$stmt = $conn->prepare($edu_query);
$stmt->bind_param("i", $candidate['user_id']);
$stmt->execute();
$edu_result = $stmt->get_result();
$education = $edu_result->fetch_all(MYSQLI_ASSOC);

// Fetch work experience
$exp_query = "SELECT * FROM tbl_emp_careerhistory WHERE user_id = ? ORDER BY start_date DESC";
$stmt = $conn->prepare($exp_query);
$stmt->bind_param("i", $candidate['user_id']);
$stmt->execute();
$exp_result = $stmt->get_result();
$experience = $exp_result->fetch_all(MYSQLI_ASSOC);

// Calculate age
$birth_date = new DateTime($candidate['birth_date']);
$today = new DateTime();
$age = $birth_date->diff($today)->y;

// Format the response
$response = [
    'basic_info' => [
        'name' => $candidate['firstName'] . ' ' . $candidate['lastName'],
        'email' => $candidate['emailAddress'],
        'phone' => $candidate['mobileNumber'],
        'address' => $candidate['address'],
        'age' => $age,
        'gender' => $candidate['gender'],
        'education' => $candidate['highest_edu'],
        'experience' => $candidate['years_of_experience'] . ' years'
    ],
    'skills' => $candidate['skills'] ? explode(',', $candidate['skills']) : [],
    'languages' => $candidate['languages'] ? explode(',', $candidate['languages']) : [],
    'education' => $education,
    'experience' => $experience,
    'resumes' => array_map(function($file) {
        return [
            'name' => basename($file['file_inserted']),
            'dir' => $file['file_inserted']
        ];
    }, $files)
];

echo json_encode($response);
?> 