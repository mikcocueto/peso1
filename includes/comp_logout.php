<?php
session_start();
session_destroy();
header("Location: ../comp_login.php");
exit();
?>
