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
</head>
<body>
    <h1>Company Verification Requests</h1>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Verification File</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($verification_requests)) { 
            $company_id = $row['comp_id'];
            $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_company WHERE company_id='$company_id'");
            $company = mysqli_fetch_assoc($company_result);
        ?>
        <tr>
            <td><?php echo $company['companyName']; ?></td>
            <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank">View File</a></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="verification_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h1>Accepted Verification Requests</h1>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Verification File</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($accepted_requests)) { 
            $company_id = $row['comp_id'];
            $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_company WHERE company_id='$company_id'");
            $company = mysqli_fetch_assoc($company_result);
        ?>
        <tr>
            <td><?php echo $company['companyName']; ?></td>
            <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank">View File</a></td>
        </tr>
        <?php } ?>
    </table>

    <h1>Rejected Verification Requests</h1>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Verification File</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($rejected_requests)) { 
            $company_id = $row['comp_id'];
            $company_result = mysqli_query($conn, "SELECT companyName FROM tbl_company WHERE company_id='$company_id'");
            $company = mysqli_fetch_assoc($company_result);
        ?>
        <tr>
            <td><?php echo $company['companyName']; ?></td>
            <td><a href="<?php echo substr($row['dir_business_permit'], 3); ?>" target="_blank">View File</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
