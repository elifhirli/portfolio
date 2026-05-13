<?php
require_once "includes/admin_auth.php";

logoutAdmin();

header('Location: /portfolio/admin-login.php');
exit;
?>
