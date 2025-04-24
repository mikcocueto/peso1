<?php
session_start();
require "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['category_id'])) {
        $category_id = $_POST['category_id'];

        // Check if the category is already added
        $query = "SELECT id FROM tbl_emp_category_preferences WHERE emp_id = ? AND category_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "Category already added.";
        } else {
            // Add the new category preference
            $query = "INSERT INTO tbl_emp_category_preferences (emp_id, category_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $category_id);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Category added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add category.";
            }
        }
    } elseif (isset($_POST['remove_category_id'])) {
        $remove_category_id = $_POST['remove_category_id'];

        // Remove the category preference
        $query = "DELETE FROM tbl_emp_category_preferences WHERE id = ? AND emp_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $remove_category_id, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Category removed successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to remove category.";
        }
    }

    $stmt->close();
    $conn->close();
    header("Location: ../../employee/emp_dashboard.php");
    exit();
}
?>
