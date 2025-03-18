<?php
include('../includes/db_connect.php'); // Include database connection
include('../includes/admin/admin_comp_ver_process.php'); // Include processing file

// Fetch verification requests
$verification_requests = mysqli_query($conn, "SELECT * FROM tbl_comp_verification WHERE status='pending'");
$accepted_requests = mysqli_query($conn, "SELECT * FROM tbl_comp_verification WHERE status='accepted'");
$rejected_requests = mysqli_query($conn, "SELECT * FROM tbl_comp_verification WHERE status='rejected'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Verify Company</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
        }
        .header img {
            height: 50px;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
            position: relative;
            width: 100%;
        }
        .container {
            flex: 1;
        }
        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-accept {
            background-color: #007bff;
            color: white;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        .btn-accept:hover {
            background-color: #0056b3;
        }
        .btn-reject:hover {
            background-color: #b02a37;
        }
        .btn-view:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Public Employment Service Office</h2>
        <img src="../assets/logo.png" alt="Company Logo">
    </div>

    <div class="container mt-4">
        <h1>Company Verification Requests</h1>
        <table class="table table-bordered table-striped">
            <tr class="table-primary">
                <th>Company Name</th>
                <th>Verification File</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($verification_requests)) { 
                $company_id = $row['comp_id'];
                $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_comp_info WHERE company_id='$company_id'");
                $company = mysqli_fetch_assoc($company_result);
            ?>
            <tr>
                <td><?php echo $company['companyName']; ?></td>
                <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank" class="btn btn-view btn-sm">View File</a></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="verification_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="accept" class="btn btn-accept btn-sm">Accept</button>
                        <button type="submit" name="action" value="reject" class="btn btn-reject btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>

        <h1>Accepted Verification Requests</h1>
        <table class="table table-bordered table-striped">
            <tr class="table-success">
                <th>Company Name</th>
                <th>Verification File</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($accepted_requests)) { 
                $company_id = $row['comp_id'];
                $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_comp_info WHERE company_id='$company_id'");
                $company = mysqli_fetch_assoc($company_result);
            ?>
            <tr>
                <td><?php echo $company['companyName']; ?></td>
                <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank" class="btn btn-view btn-sm">View File</a></td>
            </tr>
            <?php } ?>
        </table>

        <h1>Rejected Verification Requests</h1>
        <table class="table table-bordered table-striped">
            <tr class="table-danger">
                <th>Company Name</th>
                <th>Verification File</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($rejected_requests)) { 
                $company_id = $row['comp_id'];
                $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_comp_info WHERE company_id='$company_id'");
                $company = mysqli_fetch_assoc($company_result);
            ?>
            <tr>
                <td><?php echo $company['companyName']; ?></td>
                <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank" class="btn btn-view btn-sm">View File</a></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="footer">
        <p>Contact us: email@example.com | Phone: +123 456 7890</p>
        <p>
            <a href="#" class="text-white me-3">Facebook</a>
            <a href="#" class="text-white me-3">Twitter</a>
            <a href="#" class="text-white">LinkedIn</a>
        </p>
    </div>
</body>
</html>
