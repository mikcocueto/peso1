<?php
session_start();
session_unset();
session_destroy();
header("Location: ../employee/emp_login.php");
exit();
?>
