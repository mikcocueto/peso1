<?php
session_start();
session_destroy();
header("Location: ../company/comp_login.php");
exit();
?>
