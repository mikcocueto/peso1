<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_id = $_POST['verification_id'];
    $action = $_POST['action'];
    
    if ($action == 'accept') {
        // Update tbl_comp_verification status
        $update_verification = "UPDATE tbl_comp_verification SET status='accepted' WHERE id='$verification_id'";
        mysqli_query($conn, $update_verification);
        
        // Get comp_id from tbl_comp_verification
        $result = mysqli_query($conn, "SELECT comp_id FROM tbl_comp_verification WHERE id='$verification_id'");
        $row = mysqli_fetch_assoc($result);
        $company_id = $row['comp_id'];
        
        // Update tbl_company company_verified
        $update_company = "UPDATE tbl_company SET company_verified=1 WHERE company_id='$company_id'";
        mysqli_query($conn, $update_company);
    } elseif ($action == 'reject') {
        // Update tbl_comp_verification status
        $update_verification = "UPDATE tbl_comp_verification SET status='rejected' WHERE id='$verification_id'";
        mysqli_query($conn, $update_verification);
    }
}
?>
